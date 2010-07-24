<?php

class DB {
		
    private $link;
    private $results;

    function __construct() {
            require(dirname(__FILE__) . '/config.php'); // this works
            $this->link = mysql_connect('localhost', $dbusername, $dbpassword, true);
            mysql_select_db($dbname, $this->link);
            return;
    }

	function award_exp($uid,$act,$exp_value) {
		$query = sprintf("INSERT INTO experience (uid,act,value) VALUES (%d,'%s',%d)", $uid,mysql_real_escape_string($act),$exp_value);
		mysql_query($query);
		return;
	}	
	
	function calculate_leaderboard() {
		$query = sprintf("set @cnt :=0");
		mysql_query($query);
		$query = sprintf("select @cnt := @cnt+1 as rank,dt.* FROM (SELECT users.username,sum(experience.value) as score from experience,users where users.id=experience.uid group by uid order by score desc limit 50) as dt");
		
		$res = mysql_query($query);
		$ret = array();
		while($row = mysql_fetch_assoc($res)) {
			array_push($ret, $row);			
		}
		return $ret;
	}
	
	function calculate_rank($uid) {
		
		$scoreboard_query = sprintf("select count(distinct uid) as scoreboard_size from experience");
		$scoreboard_res = mysql_query($scoreboard_query);
		$scoreboard_row = mysql_fetch_assoc($scoreboard_res);
		
		
		$leaderboard_creation_query = sprintf("create temporary table leaderboard (rank int not null, uid int not null, score int not null default 0) engine=memory");
		mysql_query($leaderboard_creation_query);
		$count_initialization_query = sprintf("set @cnt :=0");
		mysql_query($count_initialization_query);

//		$leaderboard_insertion_query = sprintf("insert into leaderboard(rank,uid,score) select @cnt := @cnt+1 as rank,uid,sum(value) as score from experience group by uid order by score desc");
		$leaderboard_insertion_query = sprintf("insert into leaderboard(rank,uid,score) select @cnt := @cnt+1 as rank,dt.* FROM (SELECT experience.uid,sum(experience.value) as score from experience group by uid order by score desc limit 50) as dt");
		$leaderboard_result = mysql_query($leaderboard_insertion_query);
		


		$rank_query = sprintf("select rank from leaderboard where uid=%d", $uid);
		$rank_result = mysql_query($rank_query);
		$rank_row = mysql_fetch_assoc($rank_result);


		$drop_leaderboard_query = sprintf("drop table leaderboard");
		mysql_query($drop_leaderboard_query);		
		
		return array($rank_row['rank'],$scoreboard_row['scoreboard_size']);
	}
	
	function calculate_score($uid) {
		$query = sprintf("SELECT sum(value) AS score FROM experience WHERE uid=%d", $uid);
		$res = mysql_query($query);
		$row = mysql_fetch_assoc($res);
		return $row['score'];
	}

	function create_user($email, $pw, $username, $source) {
		$query = sprintf("INSERT INTO users (email,pw,username,source,last_login) VALUES ('%s','%s','%s','%s',now())", mysql_real_escape_string($email), md5($pw), mysql_real_escape_string($username), mysql_real_escape_string($source));
		mysql_query($query);
		return mysql_insert_id();		
	}
	
	function get_notifications($uid) {
		$query = sprintf("SELECT message,add_date FROM notification_queue WHERE uid=%d AND is_read=0 ORDER BY add_date desc", $uid);
		$res = mysql_query($query);
		$ret = array();
		while($row = mysql_fetch_assoc($res)) {
			array_push($ret, array('message'=>$row['message'], 'date'=>$row['add_date']));
		}
		$this->mark_notifications_read($uid);
		return $ret;
	}
	
	function get_notification_count($uid) {
		$query = sprintf("SELECT COUNT(*) as notification_count FROM notification_queue WHERE is_read=0 AND uid=%d", $uid);
		$res = mysql_query($query);
		$row = mysql_fetch_assoc($res);
		return $row['notification_count'];
	}	
	
	function get_unreviewed_ant($uid) {
		$query = sprintf("SELECT ant,event,u_distortions,u_uid FROM ant_queue WHERE r_uid=0 LIMIT 1");
		$res = mysql_query($query);
		$row = mysql_fetch_assoc($res);
		return $row;
	}
	
	function insert_notification($uid,$message) {
		$query = sprintf("INSERT INTO notification_queue (uid,message) VALUES (%d,'%s')", $uid, mysql_real_escape_string($message));
		mysql_query($query);
		return;
	}
	
	function login_user($email, $pw) {
		$query = sprintf("SELECT id,username FROM users WHERE email='%s' AND pw='%s'", mysql_real_escape_string($email),md5($pw));
		$res = mysql_query($query);
		if(mysql_num_rows($res)>0) {
			$query = sprintf("UPDATE users SET last_login=now() WHERE email='%s'", mysql_real_escape_string($email));
			mysql_query($query);
			$row = mysql_fetch_assoc($res);
			return array('uid' => $row['id'], 'username' => $row['username']);			
		}
		else {
			return false;
		}
	}
	
	private function mark_notifications_read($uid) {
		$query = sprintf("UPDATE notification_queue SET is_read=1,read_date=now() WHERE is_read=0 AND uid=%d", $uid);
		$res = mysql_query($query);
		return;
	}
	
	function save_ant($uid,$ant,$event,$distortions) {
		$query = sprintf("INSERT INTO ant_queue (u_uid,ant,event,u_distortions) VALUES (%d,'%s','%s','%s')", $uid, mysql_real_escape_string($ant),mysql_real_escape_string($event),mysql_real_escape_string($distortions));
		mysql_query($query);
		return;
	}
	
	function update_ant($u_uid, $ant,$event,$u_distortions, $r_uid, $r_distortions, $r_comments) {
		$query = sprintf("UPDATE ant_queue SET r_uid=%d,r_distortions='%s',r_comments='%s' WHERE u_uid=%d AND event='%s' AND u_distortions='%s'", $r_uid,mysql_real_escape_string($r_distortions),mysql_real_escape_string($r_comments),$u_uid,mysql_real_escape_string($event),mysql_real_escape_string($u_distortions));
		mysql_query($query);
		return;		
	}

}


?>