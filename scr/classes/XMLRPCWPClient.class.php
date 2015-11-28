<?php 

class XMLRPCWPClient extends IXR_Client{

	private $_login;
	private $_password;

	function __construct($xml_rpc_server_url, $login, $password){
		parent::IXR_Client($xml_rpc_server_url);

		$this->_login = $login;
		$this->_password = $password;
	}


	function term_exist($taxonomy, $term_name){

		$args = array(
			0, 
			$this->_login, 
			$this->_password, 
			$taxonomy,
			$filter = array()
		);
					
		if( $this->query('wp.getTerms', $args) ){

			$terms =  $this->getResponse();
			$i = 0;
			while(isset($terms[$i])){
				if($terms[$i]['name'] === $term_name)	return true;
				$i++;
			}

		}else{
			echo "Taxonomy name wrong / or you do not have rights to post on this blog";	
			return false;
		}

		return false;
	}




	function add_wp_category($name, $desc, $parent=false){
		return $this->add_wp_taxonomy('category', $name, $desc, $parent);
	}



	function add_wp_tag($name, $desc){
		return $this->add_wp_taxonomy('post_tag', $name, $desc);
	}



	function add_wp_taxonomy($taxonomy, $name, $desc, $parent=false){

		$taxonomy_params = array(
												'taxonomy'		=> $taxonomy, 
												'name'				=> $name, 
												'description' => $desc
											);

		if($parent) $taxonomy_params['parent'] = $parent;

		$args = array(
			0, 
			$this->_login, 
			$this->_password, 
			$taxonomy_params
		);
		
		$this->query('wp.newTerm', $args);

		return $this->getResponse();
	}



function add_wp_post($title, $body, $terms = false, $term_names = false, $post_thumbnail = false ){

		$post_params = array(
			'post_title'   => $title,
			'post_content' => $body
		);

		if($terms)
			$post_params['terms']	= $terms;

		if($term_names)
			$post_params['term_names'] = $term_names;
		
		if($post_thumbnail)
			$post_params['post_thumbnail'] = $post_thumbnail;

		$args = array(
			0, 
			$this->_login, 
			$this->_password, 
			$post_params
		);

		return $this->query('wp.newPost', $args) ? $this->getResponse() : false;
	}
}


