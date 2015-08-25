	<?php
	error_reporting(0);
	/**
		* Get User Details and Add into database.
		* Created by: MD. Shamsad Ahmed
	
	*/
	include_once('DBConnect.php'); //Database Connection
	include_once('Abstract/classResponse.php');//
	function addNewsFeeds()
	{		
		 
		$rm=new Response_Methods();		
		if($_SERVER['REQUEST_METHOD']=="GET")
		{
		$result=$rm->inValidServerMethod();
		return $result;
		}
		
		  //Check request url is https or not
       if(!empty($_SERVER["HTTPS"])){
			
			if($_SERVER["HTTPS"]!=="off") {
			
		 $userId = trim($_POST['userId']);
		 $post_image = trim($_POST['post_image']);
		 $post_description = $rm->cleanData(trim($_POST['post_description']));
		 $location_lat = $rm->cleanData(trim($_POST['location_lat']));
		 $location_lang = $rm->cleanData(trim($_POST['location_lang']));

				
		if($userId == "" || $post_description == "")
		{
			
			$result = $rm->fields_validation();		
			return $result;
		}
		
		else
		{
			
			date_default_timezone_set('Asia/Calcutta'); 
            $createdDate=date('Y-m-d H:i:s');
			$getList = array();
			
			
			//preparing list and inserting values in news_feed_table table
			
			$getInsertFieldValue['user_id_fk']=$userId;	
			//$getInsertFieldValue['post_image_f']=$post_image;				
			$getInsertFieldValue['post_description_f']=$post_description;
			$getInsertFieldValue['location_lang_f']=$location_lang;			
			$getInsertFieldValue['location_lat_f']=$location_lat;
			$getInsertFieldValue['post_date_f']=$createdDate;								
								
			$lastInserted_post_id=$rm->insert_record($getInsertFieldValue,'news_feeds_t');
			
			if(!empty($lastInserted_post_id))
					{
					
					$IMAGEURLBASEURL=BASEURL.'/images/';					
					$userImageBaseURL="images/$username";
					$username=$rm->idToValue('user_name_f','user_details_t','user_id',$userId);
					
					$userImageBaseURL="images/$username";
					
					if (!is_dir($userImageBaseURL)) 
						// is_dir - tells whether the filename is a directory
						{
							//mkdir - tells that need to create a directory
							
							mkdir($userImageBaseURL);
							mkdir($userImageBaseURL.'/profile_pics/');
							mkdir($userImageBaseURL.'/post_photos/');
						}
						
					$rand=rand(00,99999);
				
					$img = 'data:image/png;base64,'.$post_image.'';
					$img = str_replace('data:image/png;base64,', '', $img);
					$img = str_replace(' ', '+', $img);
					$data = base64_decode($img);
					$image ='post_photos'.$rand.'.png';
					file_put_contents($userImageBaseURL.'/post_photos/'.$image, $data);	
					//file_put_contents($userImageBaseURL.'/profile_pics/'.$image, $data);	
					$IMAGEURL = $IMAGEURLBASEURL.$username.'/post_photos/'.$image;		
					
					$getUpdatePostPic['post_image_f']=$IMAGEURL;	
					$updateResult=$rm->update_record($getUpdatePostPic,'news_feeds_t','post_id',$lastInserted_post_id);
					//echo 'test';					
					//$result=$rm->getPosts($userId);
					$result=$rm->postCreationSuccessJson();	
					return $result;												
					}
					
					else
					{
					$result=$rm->postCreationFailJson();
					return $result;	
					}
				
		}// end of else first
		
		}
		else {
			
				$result = $rm->ssl_error();
			    return $result;
			}
			
		} 
		else {
		      
			  $result = $rm->ssl_error();
			  return $result;
		}
									
		
   }		
	
	echo addNewsFeeds();
?>