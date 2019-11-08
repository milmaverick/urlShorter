<?php

class IndexModel extends Model
{
  protected static $chars = "abcdfghjkmnpqrstvwxyz|ABCDFGHJKLMNPQRSTVWXYZ|0123456789";
  protected static $table = "short_urls";
  protected static $checkUrlExists = true;
  protected static $codeLength = 4;

  public function urlToShortCode($url){
        if(empty($url)){
            return "No URL was supplied.";
        }

        if($this->validateUrlFormat($url) == false){
            return "URL does not have a valid format.";
        }

        if(self::$checkUrlExists){
            if (!$this->verifyUrlExists($url)){
                return "URL does not appear to exist.";
            }
        }

        $shortCode = $this->urlExistsInDB($url);
        if($shortCode == false){
            $shortCode = $this->createShortCode($url);
        }

        return HOST."/?c=".$shortCode;
    }

// проверка правильности адреса URL--------------------------
    protected function validateUrlFormat($url){
        return filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
    }

 //соединение с адресом URL и проверки, что не возвращается ошибка 404
    protected function verifyUrlExists($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return (!empty($response) && $response != 404);
    }

// опрашивает базу данных на предмет наличия в ней длинного адреса URL----------------------------------
    protected function urlExistsInDB($url){
        $query = "SELECT short_code FROM ".self::$table." WHERE long_url = :long_url LIMIT 1";
        $stmt = $this->db->prepare($query);
        $params = array(
            "long_url" => $url
        );
        $stmt->execute($params);

        $result = $stmt->fetch();
        return (empty($result)) ? false : $result["short_code"];
    }

//создание shortcode--------------------------------------------------------------
    protected function createShortCode($url){
        $shortCode = $this->generateRandomString(self::$codeLength);
        $id = $this->insertUrlInDB($url, $shortCode);
        return $shortCode;
    }

//генерация случайной строки -----------------------------------------------
    protected function generateRandomString($length = 6){
        $sets = explode('|', self::$chars);
        $all = '';
        $randString = '';
        foreach($sets as $set){
            $randString .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++){
            $randString .= $all[array_rand($all)];
        }
        $randString = str_shuffle($randString);
        return $randString;
    }

// вставка длинного адреса URL в базу данных--------------------------------------------------------

    protected function insertUrlInDB($url, $code){
        $query = "INSERT INTO ".self::$table." (long_url, short_code) VALUES (:long_url, :short_code)";
        $stmnt = $this->db->prepare($query);
        $params = array(
            "long_url" => $url,
            "short_code" => $code,
        );
        $stmnt->execute($params);

        return $this->db->lastInsertId();
    }

    public function shortCodeToUrl($code, $increment = true){
        if(empty($code)) {
            return "No short code was supplied.";
        }

        if($this->validateShortCode($code) == false){
            return "Short code does not have a valid format.";
        }

        $urlRow = $this->getUrlFromDB($code);
        if(empty($urlRow)){
          return "Short code does not appear to exist.";
        }

        if($increment == true){
            $this->incrementCounter($urlRow["id"]);
        }

        return $urlRow["long_url"];
    }

// выполняет проверку, что короткий код корректный-----------------------------------------------------------
    protected function validateShortCode($code){
        $rawChars = str_replace('|', '', self::$chars);
        return preg_match("|[".$rawChars."]+|", $code);
    }

//запрашивает базу данных с полученным коротким кодом -----------------------------------------------
    protected function getUrlFromDB($code){
        $query = "SELECT id, long_url FROM ".self::$table." WHERE short_code = :short_code LIMIT 1";
        $stmt = $this->db->prepare($query);
        $params=array(
            "short_code" => $code
        );
        $stmt->execute($params);

        $result = $stmt->fetch();
        return (empty($result)) ? false : $result;
    }

//для увеличения счетчика обращений к короткому коду.-----------------------------------------
    protected function incrementCounter($id){
        $query = "UPDATE ".self::$table." SET hits = hits + 1 WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $params = array(
            "id" => $id
        );
        $stmt->execute($params);
    }
}


?>
