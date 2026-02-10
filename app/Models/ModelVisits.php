<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelVisits extends Model
{
    protected $table            = 'visits';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['customer_id', 'latitude', 'longitude', 'photo_path', 'visit_date', 'notes', 'synced', 'user_id'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function byUserToDay($user_id) 
    {
        $db = $this->db->table('visits v');
        $db->select('v.customer_id, v.latitude, v.longitude, photo_path, v.visit_date, v.notes, v.synced, v.created_at, v.updated_at, c.name');
        $db->join('customers c', 'v.customer_id=c.id', 'left');
        $db->where([ 'user_id' => $user_id, 'v.visit_date' => date('Y-m-d')]);
        $db->orderBy('v.created_at', 'DESC');
        $data = $db->get();
        if(!$data) return false;
        return $data->getResult();
    }

    public function byDate($tgl_awal, $tgl_akhir) 
    {
        $db = $this->db->table('visits v');
        $db->select('v.customer_id, v.latitude, v.longitude, photo_path, v.visit_date, v.notes, v.synced, v.created_at, v.updated_at, c.name');
        $db->join('customer c', 'v.customer_id=c.id', 'left');
        $db->where('v.visit_date BETWEEN "'.$tgl_awal.'" AND "'.$tgl_akhir.'"');
        $db->orderBy('v.created_at', 'DESC');
        $data = $db->get();
        if(!$data) return false;
        return $data->getResult();
    }

    public function byCustomer($customer_id, $tgl_awal, $tgl_akhir) 
    {
        $db = $this->db->table('visits v');
        $db->select('v.customer_id, v.latitude, v.longitude, photo_path, v.visit_date, v.notes, v.synced, v.created_at, v.updated_at, c.name');
        $db->join('customer c', 'v.customer_id=c.id', 'left');
        $db->where('v.customer_id', $customer_id);
        $db->where('v.visit_date BETWEEN "'.$tgl_awal.'" AND "'.$tgl_akhir.'"');
        $db->orderBy('v.created_at', 'DESC');
        $data = $db->get();
        if(!$data) return false;
        return $data->getResult();
    }

    public function byUser($user_id, $tgl_awal, $tgl_akhir) 
    {
        $db = $this->db->table('visits as v');
        $db->select('v.customer_id, v.latitude, v.longitude, photo_path, v.visit_date, v.notes, v.synced, v.created_at, v.updated_at, c.name');
        $db->join('customers as c', 'v.customer_id=c.id', 'left');
        $db->where('v.user_id', $user_id);
        $db->where('v.visit_date BETWEEN "'.$tgl_awal.'" AND "'.$tgl_akhir.'"');
        $db->orderBy('v.created_at', 'DESC');
        $data = $db->get();
        if(!$data) return false;
        return $data->getResult();
    }
}
