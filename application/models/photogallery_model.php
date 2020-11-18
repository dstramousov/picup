<?php

class Photogallery_model extends UP_Model {

    function __construct($id=NULL)
	{	
		$this->table_name = 'gallery';
        parent::__construct($this->table_name,$id);
    }		
	
	
	function getByINternalName($_name)
	{
		$_ret = NULL;
		$query = $this->db->query('SELECT *, LEFT(created,10) as foldername FROM '.$this->table_name.' WHERE internal_name='.$this->db->escape($_name).' LIMIT 1'); 
		
		if($query->result()){
			$_ret = $query->row();
			$this->_mapper($_ret);
		}

		return $_ret;
	}
	
    /**
     * Return count of galleries for needed user. If $_user_id = NULL function return total count of gallery in the system
     *
     * @access private
     * @return boolean is user authorized 
     */
	function getTotalGallery($_user_id = NULL)
	{
		if($_user_id){
			$query = $this->db->get_where($this->table_name, array('user_id' => $_user_id));
			return($this->db->affected_rows());
		} else{
			return $this->db->count_all($this->table_name);
		}
	} // end of function 

    /**
     * Return count of galleries for needed user. If $_user_id = NULL function return total count of gallery in the system
     *
     * @access private
     * @return boolean is user authorized 
     */
	function getByParams($sortingField, $sortingOrder, $firstRowIndex, $rowsPerPage, $user_id = NULL)
	{
		//dump($firstRowIndex, $rowsPerPage);
		$this->db->limit($rowsPerPage, $firstRowIndex);
		$this->db->order_by($sortingField, $sortingOrder);
		
		if($user_id){
			$this->db->where('user_id', $user_id);
		}
		
		$query = $this->db->get($this->table_name);
		
		return $query->result_array();
		
	} // end of function 
	
    /**
     * Return all photos for gellery 
     *
     * @access private
     * @return boolean is user authorized 
     */
	function getAllGalleryByUser($_id = NULL)
	{
		$__ID = NULL;
		$__ID = $this->user->id;
		if($_id){$__ID = $_id;}
			
		$_ret = array();
		if(!$__ID){return $_ret;}
		
		$this->db->order_by('created', 'desc');
		$this->db->where('user_id', $__ID);
		
		if(!$this->user->isDefinite()){
			$this->db->where('user_perm', 'all');
			$this->db->where('status', 'active');
		}
		
		$query = $this->db->get($this->table_name);
		
		if ($query->num_rows() > 0)
		{
			foreach($query->result_array() as $row)
			{						
				$row['edit_url']		= '<a href="'.base_url().'editgallery/'.$row['internal_name'].'">'.'<img src="'.base_url().'images/edit.png'.'" />'.'</a>';
				$row['delete_url']		= '<a href="'.base_url().'deletegallery/'.$row['internal_name'].'">'.'<img src="'.base_url().'images/delete.png'.'" />'.'</a>';				
				$row['description']		= get_shortened($row['description'], 20);
				$row['created_uf'] = systemFormatDateTime($row['created'], false);
				
				$ph = $this->getPhotos($row['id']);
				
				$row['total_photo']		= count($ph);
				if($ph){
					$_u = new User_model($ph[0]['user_id']);
					$row['first_picture']	= base_url().$this->config->item('images_users').'/'.$_u->nickname.'/'.$row['internal_name'].'/'.$this->config->item('thumb_prefix').$ph[0]['internal_name'].$ph[0]['extension'];
				} else {
					$row['first_picture']   = base_url().'images/sample_image.jpg';
				}
				
				array_push($_ret, $row);
			}
		}
		return $_ret;
	} // end of function 
	
    /**
     * Return all photos for gellery 
     *
     * @access private
     * @return boolean is user authorized 
     */
	function delete($_gallery_id = NULL)
	{
		if($_gallery_id){
			$this->fetchByID($_gallery_id);
		}
		
		$photos = $this->getPhotos();
		
		foreach($photos as $p)
		{
			//dump($this, $p);
			$folder_file = $this->config->item('images_users').'/'.$this->user->nickname.'/'.$this->internal_name.'/';
			
			$file	= $folder_file.$p['internal_name'].$p['extension'];
			$thumb	= $folder_file.$this->config->item('thumb_prefix').$p['internal_name'].$p['extension'];
			
			// db remove 
			$this->db->delete('photo', array('id' => $p['id'])); 
			$this->db->delete('ext_links', array('internal_name' => $p['internal_name']));
			$this->db->delete('comments', array('photo_id' => $p['id']));
			
			// filesystem remove
			unlink($file);
			unlink($thumb);
		}
		
		$this->db->delete($this->table_name, array('id' => $this->id)); 		
	}
	
	
		
		
    /**
     * Return all photos for gellery 
     *
     * @access private
     * @return boolean is user authorized 
     */
	function getCountViews($_gallery_id = NULL)
	{		
		$ret = 0;
		$photos = $this->getPhotos($_gallery_id);
		foreach($photos as $p){
			$ret += $p['countseeit'];
		}
		
		return $ret;
	}
		
    /**
     * Return all photos for gellery 
     *
     * @access private
     * @return boolean is user authorized 
     */
	function getPhotos($_gallery_id = NULL)
	{		
		if($_gallery_id){
			$this->fetchByID($_gallery_id);
		}
		
		if(!$this->isDefinite()){
			return NULL;
		}
		
		$this->db->order_by('created', 'desc');		
		$this->db->where('gallery_id', $this->id);
		$query = $this->db->get($this->Photo_model->table_name);		
		
		return $query->result_array();
	} // end od function 
} // end of class