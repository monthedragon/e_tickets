<?php
class Export_model extends CI_Model {
    var $retVal = array();

    public function __construct()
    {
        $this->load->database();
    }

    public function get_column($table){
        return $this->db->list_fields($table);
    }

    public function set_default_condition(){
        $post = $this->input->get();

        if(!empty($post['start_calldate']))
            $this->db->where('contact_list.calldate >=',"{$post['start_calldate']} 00:00:00");

        if(!empty($post['end_calldate']))
            $this->db->where('contact_list.calldate <=',"{$post['end_calldate']} 23:59:59");

        if(isset($post['agents']))
            $this->db->where_in('contact_list.assigned_agent',$post['agents']);

        if(isset($post['lead_identities']))
            $this->db->where_in('contact_list.lead_identity',$post['lead_identities']);


    }

    public function do_export(){
        $post = $this->input->get();

        //select * in default
        $selectedCols = '*';

        //set selected columns
        if(isset($post['columns']))
            $selectedCols = implode(',',$post['columns']);

        $this->db->select($selectedCols.',id as contact_list_id')->from('contact_list');

        $this->set_default_condition();

        $result  =  $this->db->get()->result_array();


        return $result;
    }

    public function get_no_of_supple(){
        $retValAarr = array();
        $post = $this->input->get();

        $this->db->select('contact_list.id,count(*) as CTR')->from('contact_list')
            ->join('supplementary',"supplementary.baserecid = contact_list.id AND callresult ='AG'",'INNER');
        $this->set_default_condition();


        $result  =  $this->db->group_by('contact_list.id')->get()->result_array();


        foreach($result as $detail){
            $retValAarr[$detail['id']] = $detail['CTR'];
        }

        return $retValAarr;
    }

    public function get_no_of_touched(){
        $retValAarr = array();
        $post = $this->input->get();

        $this->db->select('contact_list.id,count(*) as CTR')->from('contact_list')
            ->join('trans_log',"trans_log.contact_id = contact_list.id ",'INNER');
        $this->set_default_condition();


        $result  =  $this->db->group_by('contact_list.id')->get()->result_array();


        foreach($result as $detail){
            $retValAarr[$detail['id']] = $detail['CTR'];
        }

        return $retValAarr;
    }


}
