<?php
class dbcontroller
{
    private $host = '127.0.0.1';
    private $user = 'root';
    private $password = '';
    private $database = 'otojadi_db';
    private $koneksi;

    public function __construct()
    {
        $this->koneksi = $this->koneksiDB();
    }

    public function getLastInsertId()
    {
        return mysqli_insert_id($this->koneksi);
    }

    public function getAffectedRows()
    {
        return mysqli_affected_rows($this->koneksi);
    }

    public function koneksiDB()
    {
        $koneksi = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        return $koneksi;
    }

    public function getALL($sql)
    {
        $result = mysqli_query($this->koneksi, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        if (!empty($data)) {
            return $data;
        }
    }

    public function getITEM($sql)
    {
        $result = mysqli_query($this->koneksi, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row;
    }

    public function rowCOUNT($sql)
    {
        $result = mysqli_query($this->koneksi, $sql);
        $count = mysqli_num_rows($result);

        return $count;
    }

    public function runSQL($sql)
    {
        $result = mysqli_query($this->koneksi, $sql);
    }

    public function addToFavorite($userId, $productId)
    {
        $sql = "INSERT INTO favorite (id_user, id_barang) VALUES (?, ?)";
        $stmt = mysqli_prepare($this->koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $userId, $productId);
        return mysqli_stmt_execute($stmt);
    }

    public function removeFromFavorite($userId, $productId)
    {
        $sql = "DELETE FROM favorite WHERE id_user = ? AND id_barang = ?";
        $stmt = mysqli_prepare($this->koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $userId, $productId);
        return mysqli_stmt_execute($stmt);
    }

    public function isInFavorite($userId, $productId)
    {
        $sql = "SELECT COUNT(*) AS count FROM favorite WHERE id_user = ? AND id_barang = ?";
        $stmt = mysqli_prepare($this->koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $userId, $productId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['count'] > 0;
    }


}