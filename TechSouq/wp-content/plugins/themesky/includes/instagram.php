<?php 
if( !class_exists('TS_Instagram') ){
    class TS_Instagram {
        private $access_token, $option_key = 'ts_instagram_tokens';
		public $base_access_token, $number;

        public function set_base_access_token( $value ){
            $this->base_access_token = $value;
        }

        public function set_number( $value ){
            $this->number = $value;
        }

        function connect( $url ){
            $args = array(
				'timeout' => 60
				,'sslverify' => false
			);
			$response = wp_remote_get( $url, $args );

			if( ! is_wp_error( $response ) ){
				$response = json_decode( str_replace( '%22', '&rdquo;', $response['body'] ), true );
			}

			if( isset($response['data']) ){
				return $response['data'];
			}
			else{
				return $response;
			}
        }

        function maybe_clean_token(){
			$split_token = explode( ' ', trim( $this->base_access_token ) );
			$this->base_access_token = preg_replace("/[^A-Za-z0-9 ]/", '', $split_token[0] );
			
			if( substr_count ( $this->base_access_token , '.' ) < 3 ){
				$this->access_token = $this->base_access_token;
				return;
			}

			$parts = explode( '.', trim( $this->base_access_token ) );
			$last_part = $parts[2] . $parts[3];
			$this->access_token = $parts[0] . '.' . base64_decode( $parts[1] ) . '.' . base64_decode( $last_part );
		}

        // Token need to be refreshed every 60 days
		function maybe_refresh_token(){	
			$need_refresh = true;
			$value = get_option($this->option_key, array());
			if( isset($value[$this->base_access_token]['timestamp']) ){
				$current_token = $value[$this->base_access_token]['refreshed_token'];
				$timestamp = $value[$this->base_access_token]['timestamp'];
				if( $timestamp > time() ){
					$need_refresh = false;
				}
				$this->access_token = $current_token;
			}
			else if( !is_array($value) ){
				$value = array();
			}
			
			if( $need_refresh ){
				$url = 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=' . $this->access_token;
				$data = $this->connect( $url );
				
				// show error here if failed
				if( isset($data['access_token']) ){
					if( !isset($value[$this->base_access_token]) ){
						$value[$this->base_access_token] = array();
					}
					$value[$this->base_access_token]['refreshed_token'] = $data['access_token'];
					$value[$this->base_access_token]['timestamp'] = time() + MONTH_IN_SECONDS; // refresh after a month
					
					// delete unuse token before saving
					foreach( $value as $t => $v ){
						if( $t != $this->base_access_token && isset($v['timestamp']) && ( $v['timestamp'] + YEAR_IN_SECONDS ) < time() ){
							unset($value[$t]);
						}
					}
					
					update_option($this->option_key, $value); // use refreshed token for future
					$this->access_token = $data['access_token'];
					return true;
				}
				else{
					return new WP_Error( 'cant_refresh_token', esc_html__( 'Can not refresh Instagram token. It may be incorrect.', 'themesky' ) );
				}
			}
			return true;
		}

        function get_user_id(){
			$value = get_option($this->option_key, array());
			
			if( isset($value[$this->base_access_token]['user_id']) ){
				return $value[$this->base_access_token]['user_id'];
			}
			
			$url = 'https://graph.instagram.com/me?fields=id,username&access_token=' . $this->access_token;
			$response = $this->connect( $url );
			
			if( isset($response['id']) ){
				if( !isset($value[$this->base_access_token]) ){
					$value[$this->base_access_token] = array();
				}
				$value[$this->base_access_token]['user_id'] = $response['id'];
				update_option($this->option_key, $value);
				return $response['id'];
			}
			else{
				return new WP_Error( 'invalid_response', esc_html__( 'Unable to communicate with Instagram.', 'themesky' ) );
			}
		}

        function get_data_with_token(){
			$user_id = $this->get_user_id();
			
			if( is_wp_error($user_id) ){
				return $user_id;
			}
			
			$number = $this->number * 2; // prevent have video/album
			
			$url = 'https://graph.instagram.com/'.$user_id.'/media?fields=media_url,caption,id,media_type,permalink&limit='.$number.'&access_token=' . $this->access_token;
			
			$response = $this->connect( $url );
			
			if( !is_array($response) ){
				return new WP_Error( 'invalid_response', esc_html__( 'Instagram has returned invalid data.', 'themesky' ) );
			}
			
			if( isset($response['error']['message']) ){
				return new WP_Error( 'error_response', $response['error']['message'] );
			}
			
			$items = array();
			foreach( $response as $node ){
				if( !isset($node['media_type']) || $node['media_type'] != 'IMAGE' ){
					continue;
				}
				$item = array();
				$item['permalink'] =  $node['permalink'];
				$item['media_url'] =  $node['media_url'];
				$item['caption'] = isset($node['caption'])?$node['caption']:__('Instagram Image', 'themesky');
				$items[] = $item;
			}
			
			return array_slice( $items, 0, $this->number );
		}
    }
}
    