<?php 
   /*
   数据库连接初始化函数
   */

   function mysqlInit($dbms,$host,$dbname,$username,$password){
   	        $dsn="{$dbms}:host={$host};dbname={$dbname}";
   	        $pdo=new PDO($dsn,$username,$password);
   	        $pdo->query("set names utf8");
            $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
            return $pdo;

   }
   //跳转函数
   function msg($type,$msg=null,$url=null){
            $tomsg="location:msg.php?type={$type}";
            $tomsg.=$msg ? "&msg={$msg}" : "";
            $tomsg.=$url ? "&url={$url}" : "";
            header($tomsg);

   }


   function checkLogin(){
         session_start();
   	   if(isset($_SESSION['user'])){
   	   	    return true;

   	   }else{
   	   	    return false;
   	   }
   }
   //上传图片并返回图片路径
   function imgUpload($file,$userId){
            if(!is_uploaded_file($file['tmp_name'])){//判断是否通过http请求上传文件
                         msg(2,'请通过合法路径上传文件');
            }
            if(!in_array($file['type'],array('image/png','image/gif','image/jpeg','image/jpg'))){
               //判断上传类型
               msg(2,'请上传png,gif,jpeg,jpg格式图片');
            }
            $filePath="file/";//创建文路径
            $imgPath="{$userId}/".date("Y/md/",time());
            $ext=strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));//获取文件后缀名
            // var_dump($ext);
            $img=uniqid().rand(1000,9999);//存储图片名称
            // echo $img;
            if(!is_dir($filePath.$imgPath)){
               //判断指定路径是否存在，不存在则创建文件夹
               mkdir($filePath.$imgPath,0777,true);

            }

            $uploadPath=$filePath.$imgPath.$img.".".$ext;
            $imgUrl="http://localhost/php/ArtGallery/".$uploadPath;
            if(move_uploaded_file($file['tmp_name'],$uploadPath)){
               //将文件从临时目录移动到制定目录，若成功提示操作成功，跳转。
             
               return $imgUrl;
               msg(1,'操作成功','user.php');
            }else{
               msg(2,'操作失败，请重试');
            }
          

   }



//获取链接url
    // var_dump($_SERVER);
    function getUrl($page){
        $url=$_SERVER["REQUEST_SCHEME"]."://";  //http://
        $url.=$_SERVER["HTTP_HOST"];//  http://localhost
        $url.=$_SERVER["SCRIPT_NAME"];// http://localhost/php/ArtGallery/lib/fun.php
        $queryString=$_SERVER['QUERY_STRING'];//page=3$id=1&name=abc

        parse_str($queryString,$queryArr);
        if(isset($queryArr['page'])){
                unset($queryArr['page']);
        }
        $queryArr['page']=$page;
        $queryString=http_build_query($queryArr);

        $url.="?".$queryString;
        return $url;



    }





  //    创建页码函数，用来输出一个放有页码标签的字符串
        function pages($totalPage,$page,$show=5){
                $pageStr = "";
                if($totalPage > 1){
//                      设置页码起始点
                        $from = max(1,$page - intval($show / 2));
//                      设置页码的结束点
                        $to = $from + $show - 1;
                        
                        if($to > $totalPage){
                                $to = $totalPage;
                                $from = max(1,$to - $show + 1);
                        }
                        
                        $pageStr .= "<div class='page-nav'>";
                        $pageStr .= "<ul>";
                        
                        if($page > 1){
                                $pageStr .= "<li><a href='".getUrl(1)."'>首页</a></li>";
                                $pageStr .= "<li><a href='".getUrl($page-1)."'>上一页</a></li>";
                        }
                        
                        if($from > 1){
                                $pageStr .= "<li>...</li>";
                        }
                        
                        for($i=$from; $i<=$to; $i++){
                                if($i == $page){
                                        $pageStr .= "<li><span class='curr-page'>{$i}</span></li>";
                                }else{
                                        $pageStr .= "<li><a href='".getUrl($i)."'>{$i}</a></li>";
                                }
                        }
                        
                        if($to < $totalPage){
                                $pageStr .= "<li>...</li>";
                        }
                        
                        if($page < $totalPage){
                                $pageStr .= "<li><a href='".getUrl($page+1)."'>下一页</a></li>";
                                $pageStr .= "<li><a href='".getUrl($totalPage)."'>尾页</a></li>";
                        }                       
                }
                
                return $pageStr;
        }
        

 ?>