<?php
    /**
     * @property M_AllFunction $M_AllFunction
     */
    class Template{
        protected $_CI;

        function __construct(){
            $this->_CI=&get_instance();

            $this->_CI->load->model('M_AllFunction');
        }

        function display($template, $data=null){
            $data['_menulv1'] = $this->_CI->M_AllFunction->Menu("menu_lv1");
            $data['_menulv2'] = $this->_CI->M_AllFunction->Menu("menu_lv2");
            $data['_menulv3'] = $this->_CI->M_AllFunction->Menu("menu_lv3");

            // $data['notification'] = $this->_CI->M_AllFunction->CustomQuery("SELECT COUNT(id) AS permintaan, MAX(created_date) AS created_date FROM trn_permintaan_hdr WHERE is_approved = 'submitted'");

            $data['_navbar']  = $this->_CI->load->view('template/T_Navbar', $data, true);
            $data['_sidebar'] = $this->_CI->load->view('template/T_Sidebar', $data, true);
            $data['_header']  = $this->_CI->load->view('template/T_Header', $data, true);
            $data['_content'] = $this->_CI->load->view($template, $data, true);
            $data['_footer']  = $this->_CI->load->view('template/T_Footer', $data, true);

            $this->_CI->load->view('/v_template.php', $data);
        }
    }
?>