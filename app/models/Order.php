<?php
if (class_exists('Order')) return;
class Order {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function create($uid, $sid, $name, $email, $phone, $address, $total, $cart_items) {
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare("INSERT INTO orders (user_id, session_id, customer_name, customer_email, customer_phone, delivery_address, total_amount, status) VALUES (:uid,:sid,:name,:email,:phone,:addr,:total,'pending')");
            $stmt->execute([':uid'=>$uid,':sid'=>$sid,':name'=>$name,':email'=>$email,':phone'=>$phone,':addr'=>$address,':total'=>$total]);
            $oid = $this->conn->lastInsertId();
            $iStmt = $this->conn->prepare("INSERT INTO order_items (order_id, product_id, size_id, quantity, price) VALUES (?,?,?,?,?)");
            foreach ($cart_items as $item) {
                $iStmt->execute([$oid, $item['product_id'], $item['size_id'], $item['quantity'], $item['price']]);
            }
            $this->conn->commit();
            return $oid;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getAll($limit = 50) {
        return $this->conn->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT $limit")->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getItems($order_id) {
        $stmt = $this->conn->prepare("SELECT oi.*, p.name, ps.size_name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id LEFT JOIN product_sizes ps ON oi.size_id = ps.id WHERE oi.order_id = ?");
        $stmt->execute([$order_id]);
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status) {
        return $this->conn->prepare("UPDATE orders SET status = ? WHERE id = ?")->execute([$status, $id]);
    }

    public function count() { return $this->conn->query("SELECT COUNT(*) FROM orders")->fetchColumn(); }

    public function totalRevenue() { return $this->conn->query("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE status != 'cancelled'")->fetchColumn(); }

    public function countByStatus($status) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM orders WHERE status = ?");
        $stmt->execute([$status]);
        return $stmt->fetchColumn();
    }

    public function recentRevenue($days = 30) {
        $stmt = $this->conn->prepare("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) AND status != 'cancelled'");
        $stmt->execute([$days]);
        return $stmt->fetchColumn();
    }
}
?>