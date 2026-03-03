<?php
if (class_exists('Product')) return;
class Product {
    private $conn;

    public function __construct($db) { $this->conn = $db; }

    public function getAll($filters = []) {
        $query = "SELECT p.*, MIN(ps.price) as min_price FROM products p 
                  LEFT JOIN product_sizes ps ON p.id = ps.product_id WHERE 1=1";
        if (!empty($filters['category'])) $query .= " AND p.category = :category";
        if (!empty($filters['is_featured'])) $query .= " AND p.is_featured = 1";
        if (isset($filters['is_veg'])) $query .= " AND p.is_veg = :is_veg";
        $query .= " GROUP BY p.id ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        if (!empty($filters['category'])) $stmt->bindParam(':category', $filters['category']);
        if (isset($filters['is_veg'])) $stmt->bindParam(':is_veg', $filters['is_veg']);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getSizes($product_id) {
        $stmt = $this->conn->prepare("SELECT * FROM product_sizes WHERE product_id = :pid ORDER BY price ASC");
        $stmt->bindParam(':pid', $product_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCategories() {
        $stmt = $this->conn->prepare("SELECT DISTINCT category FROM products ORDER BY category");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function count() {
        return $this->conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO products (name, description, image, category, is_veg, is_featured, price) VALUES (:name, :desc, :img, :cat, :veg, :feat, :price)");
        $stmt->execute([':name'=>$data['name'],':desc'=>$data['description'],':img'=>$data['image'],':cat'=>$data['category'],':veg'=>$data['is_veg'],':feat'=>$data['is_featured'],':price'=>$data['price']]);
        $pid = $this->conn->lastInsertId();
        foreach(['Small (8")','Medium (12")','Large (16")','Extra Large (20")'] as $i => $size) {
            $mult = [1, 1.5, 2, 2.5][$i];
            $this->conn->prepare("INSERT INTO product_sizes (product_id, size_name, price) VALUES (?,?,?)")->execute([$pid, $size, round($data['price'] * $mult)]);
        }
        return $pid;
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE products SET name=:name, description=:desc, image=:img, category=:cat, is_veg=:veg, is_featured=:feat, price=:price WHERE id=:id");
        return $stmt->execute([':name'=>$data['name'],':desc'=>$data['description'],':img'=>$data['image'],':cat'=>$data['category'],':veg'=>$data['is_veg'],':feat'=>$data['is_featured'],':price'=>$data['price'],':id'=>$id]);
    }

    public function delete($id) {
        return $this->conn->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
    }
}
?>