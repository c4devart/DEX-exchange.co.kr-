<?php
/* ====================================================================== */
/* =   PAGE : ���� PHP ���̺귯�� 1.0.1                                 = */
/* = ------------------------------------------------------------------ = */
/* =   Copyright (c)  2012   KCP Inc.   All Rights Reserverd.           = */
/* ====================================================================== */

/* ====================================================================== */
/* =   ���� ���� CLASS                                                  = */
/* ====================================================================== */
class   C_CT_CLI
{
    // ���� ���� �κ�
    var    $m_dec_data;

    // ���� �ʱ�ȭ ����
    function mf_clear()
    {
        $this->m_dec_data="";        
    }

    // hash ó�� ����
    function make_hash_data( $home_dir , $str )
    {   
        $hash_data = $this -> mf_exec( $home_dir . "/bin/ct_cli" , 
                                       "lf_CT_CLI__make_hash_data",
                                       $str
                                     );

        if ( $hash_data == "" ) { $hash_data = "HS01"; }
        
        return $hash_data;
    }

    // dn_hash üũ �Լ�
    function check_valid_hash ($home_dir , $hash_data , $str )
    {
        $ret_val = $this -> mf_exec( $home_dir . "/bin/ct_cli" ,
                                     "lf_CT_CLI__check_valid_hash" ,
                                     $hash_data ,
                                     $str
                                    );

        if ( $ret_val == "" ) { $ret_val = "HS02"; }

        return $ret_val;
    }

    // ��ȣȭ ���������� ��ȣȭ
    function decrypt_enc_cert ( $home_dir, $site_cd , $cert_no , $enc_cert_data , $opt)
    {
        $dec_data = $this -> mf_exec( $home_dir . "/bin/ct_cli" ,
                                     "lf_CT_CLI__decrypt_enc_cert" ,
                                      $site_cd ,
                                      $cert_no ,
                                      $enc_cert_data ,
                                      $opt
                                    );
        if ( $dec_data == "" ) { $dec_data = "HS03"; }


        parse_str( str_replace( chr( 31 ), "&", $dec_data ), $this->m_dec_data );
    }

    // ���������� get data
    function mf_get_key_value( $name )
    {
        return  $this->m_dec_data[ $name ];
    }

    function  mf_exec()
    {
      $arg = func_get_args();

      if ( is_array( $arg[0] ) )  $arg = $arg[0];

      $exec_cmd = array_shift( $arg );

      while ( list(,$i) = each($arg) )
      {
        $exec_cmd .= " " . escapeshellarg( $i );
      }

      $rt = exec( $exec_cmd );

      return  $rt;
    }
}
?>