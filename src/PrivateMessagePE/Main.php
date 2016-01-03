<?php
namespace PrivateMessagePE;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

    public function onEnable(){
        if(!file_exists($this->getDataFolder() . "messages_" . date("Y-m-d") . ".dat")) {
            @mkdir($this->getDataFolder());
        }
    }

    public function onDisable(){
    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
        if(strtolower($command->getName()) === "pm"){
            if(!($sender instanceof Player)){
                $sender->sendMessage("Please run this command in game");
                return true;
            }
            if(!$sender->hasPermission("pm.command")){
                $sender->sendMessage("Yout dont have permissions to write a PM");
                return true;
            }
            if(!isset($args[0])){
                $sender->sendMessage($command->getUsage());
                return true;
            }

            $playerName = $sender->getName();
            $text = utf8_encode(chop(trim(implode(" ", $args[0]))));

            $message = json_encode(array(
                "playerName" => $playerName,
                "message" => $text,
                "datetime" => date("Y-m-d H:i:s")
            ));

            $this->saveMessage($message);

            $sender->sendMessage("PM written. OP will read it later");
        }
        return true;
    }

    public function saveMessage($message)
    {
        $res = fopen($this->getDataFolder() . "messages_" . date("Y-m-d") . ".dat", "a+");
        fwrite($res, $message.PHP_EOL.PHP_EOL);
        fclose($res);
    }

}