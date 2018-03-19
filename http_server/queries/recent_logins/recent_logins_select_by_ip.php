<?php

function recent_logins_select_by_ip($pdo, $ip, $count = 100)
{
    $count = (int) $count;
    $stmt = $pdo->prepare('SELECT * FROM recent_logins WHERE ip = :ip LIMIT 0 , :count');
    $stmt->bindValue(':ip', $user_id, PDO::PARAM_STR);
    $stmt->bindValue(':count', $count, PDO::PARAM_INT);
    $result = $stmt->execute();
    
    if($result === false) {
        return false;
    } else {
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }   
}

?>
