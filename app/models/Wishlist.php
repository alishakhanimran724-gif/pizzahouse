<?php
if (class_exists('Wishlist')) return;
class Wishlist {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function add($uid, $sid, $pid) {
        if ($this->exists($uid, $sid, $pid)) return true;
        $stmt = $this->conn->prepare(
            "INSERT INTO wishlist (user_id, session_id, product_id) VALUES (?,?,?)"
        );
        return $stmt->execute([$uid, $sid, $pid]);
    }

    public function remove($uid, $sid, $pid) {
        $cond = $uid ? "user_id = :key" : "session_id = :key";
        $stmt = $this->conn->prepare("DELETE FROM wishlist WHERE product_id = :pid AND $cond");
        return $stmt->execute([':pid' => $pid, ':key' => $uid ?: $sid]);
    }

    public function exists($uid, $sid, $pid) {
        $cond = $uid ? "user_id = :key" : "session_id = :key";
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM wishlist WHERE product_id = :pid AND $cond");
        $stmt->execute([':pid' => $pid, ':key' => $uid ?: $sid]);
        return $stmt->fetchColumn() > 0;
    }

    public function getItems($uid, $sid) {
        $cond = $uid ? "w.user_id = :key" : "w.session_id = :key";
        $stmt = $this->conn->prepare(
            "SELECT w.*, p.* FROM wishlist w
             LEFT JOIN products p ON w.product_id = p.id
             WHERE $cond ORDER BY w.created_at DESC"
        );
        $stmt->execute([':key' => $uid ?: $sid]);
        return $stmt->fetchAll();
    }

    public function getCount($uid, $sid) {
        $cond = $uid ? "user_id = :key" : "session_id = :key";
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM wishlist WHERE $cond");
        $stmt->execute([':key' => $uid ?: $sid]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * On login/signup: move guest wishlist items into the user's account.
     * Skip duplicates already saved by this user.
     */
    public function mergeGuestWishlist($guest_session, $user_id) {
        $stmt = $this->conn->prepare(
            "SELECT product_id FROM wishlist WHERE session_id = ? AND user_id IS NULL"
        );
        $stmt->execute([$guest_session]);
        $guest_items = $stmt->fetchAll();

        foreach ($guest_items as $item) {
            $check = $this->conn->prepare(
                "SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?"
            );
            $check->execute([$user_id, $item['product_id']]);

            if (!$check->fetch()) {
                // Not in user's wishlist yet — assign it
                $this->conn->prepare(
                    "UPDATE wishlist SET user_id = ?, session_id = NULL WHERE session_id = ? AND product_id = ? AND user_id IS NULL"
                )->execute([$user_id, $guest_session, $item['product_id']]);
            }
        }

        // Remove leftover guest rows
        $this->conn->prepare("DELETE FROM wishlist WHERE session_id = ? AND user_id IS NULL")
                   ->execute([$guest_session]);
    }
}
?>