<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Advertiser;
use app\models\Campaign;
use app\models\CampaignData;
use app\models\Order;
use app\models\OrderData;
use app\models\Creative;
use app\models\CreativeData;

/**
 * This command.
 *
 * @author Eduard Balantsev balantsev.eduard@gmail.com
 * @since 2.0
 */
class UtilsController extends Controller
{
    const WINDOWS_EOL = "\r\n";
    const LINUX_EOL   = "\n"  ;

    const TYPE_CAMPAIGN = 'campaign';
    const TYPE_ORDER    = 'order';
    const TYPE_CREATIVE = 'creative';

    public $mapAdvertiser = [
        'yashi_advertiser_id' => 'Advertiser ID',
        'advertiser_name' => 'Advertiser Name'
    ]; 

    public $mapCount = [
        'impression_count'=> 'Impressions',
        'click_count'     => 'Clicks',
        '25viewed_count'  => '25% Viewed',
        '50viewed_count'  => '50% Viewed',
        '75viewed_count'  => '75% Viewed',
        '100viewed_count' => '100% Viewed'
    ];

    public $mapCampaign = [
        'yashi_campaign_id' => 'Campaign ID',
        'name' => 'Campaign Name',
        'yashi_advertiser_id' => 'Advertiser ID',
        'advertiser_name' => 'Advertiser Name',
    ];

    public $mapOrder = [
        'yashi_order_id' => 'Order ID',
        'name' => 'Order Name'
    ];

    public $mapCreative = [
        'yashi_creative_id' => 'Creative ID',
        'name' => 'Creative Name',
        'preview_url' => 'Creative Preview URL'
    ];

    public $defaultAction = 'check-db';
    /** @var resource for ftp server connect */
    protected $connectFtp = 0;
    /** @var string[] information for connect to FTP Server 'host' 'user' 'pass' 'template' */
    public $configFtp;
    /**
     * This command try to connect to DB and if DB not exist create it.
     * @return int Exit code
     */
    public function actionCheckDb()
    {
        echo 'Utils Controller Action check-db' . PHP_EOL;
        $dsn = Yii::$app->db->dsn;
        echo 'Try DB connect' . PHP_EOL . $dsn . PHP_EOL;
        //get name of database
        list($pdo_name,$params) = explode(':',$dsn);
        $values = [];
        foreach ( explode(';',$params) as $key_value)
        {
            list($key,$value) = explode('=',$key_value);
            $values[$key] = $value;
        }
        $db_name = $values['dbname'];
        echo 'DB name is ' . $db_name . PHP_EOL;
        try
        {
            echo Yii::$app->db->serverVersion . PHP_EOL;
        }
        catch ( \Exception $e)
        {
            // Create db if not exists
            if(1049 == $e->getCode())
            {
                // remove db_name from dsn.
                unset($values['dbname']);
                $params=[];
                foreach($values as $name => $value)
                {
                    $params[] = $name . '=' . $value;
                }
                Yii::$app->db->dsn=$pdo_name . ':' . implode(';',$params);
                echo Yii::$app->db->serverVersion . PHP_EOL;
                $result = Yii::$app->db->createCommand('CREATE DATABASE IF NOT EXISTS ' . $db_name)->execute();
                if($result == 1) {
                    echo 'DB ' . $db_name . ' was created.' . PHP_EOL;
                } else {
                    echo 'DB ' . $db_name . ' was not created.' . PHP_EOL;
                }
            } else {
                echo 'Caught exception: ' . $e->getMessage() . PHP_EOL;
            }
        }
        return ExitCode::OK;
    }
    /**
    * This command show information about FTP connect and try to connect to FTP Server.
     * @return int Exit code
     */
    public function actionCheckFtp()
    {
        echo 'Utils Controller Action check-ftp' . PHP_EOL;
        echo 'Information for connection to FTP :' . PHP_EOL . var_export(Yii::$app->params['ftp'],true);
        if($this->connectToFtp())
        {
            $list_of_file = ftp_rawlist($this->connectFtp,'.');
            echo var_export($list_of_file,true);
            $list_of_file = ftp_nlist($this->connectFtp,$this->configFtp['remote_path']);
            echo var_export($list_of_file,true);
            foreach($list_of_file as $file) {echo dirname($file) . PHP_EOL;}
            $this->loadFileToArray('yashi/Yashi_Advertisers.csv');
            return ExitCode::OK;
        }
        else
        {
            return ExitCode::UNSPECIFIED_ERROR;
        } 
    }

    public function actionGetAdvertiser()
    {
        echo 'Utils Controller Action get-advertiser' . PHP_EOL;
        if($this->connectToFtp())
        {
            $file_name = $this->configFtp['remote_path'] . str_replace('{date}','Advertisers',$this->configFtp['template']);
            echo $file_name . PHP_EOL;
            $sourc_data = $this->loadFileToArray($file_name);
            foreach($sourc_data as $advertiser)
            {
                $find = Advertiser::findOne( (int) $advertiser[$this->mapAdvertiser['yashi_advertiser_id']]);
                if( empty($find) )
                {
                    $find = new Advertiser();
                    echo 'New Advertiser' . PHP_EOL;
                }
                foreach($this->mapAdvertiser as $field_name => $key)
                {
                    $find->{$field_name} = $advertiser[$key];
                }
                $find->save();
            }
            return ExitCode::OK;
        }
        else
        {
            return ExitCode::UNSPECIFIED_ERROR;
        } 
    }

    public function actionGetData($from = '', $to = '')
    {
        echo 'Utils Controller Action get-data' . PHP_EOL;
        if(empty($from)) {
            $from = strtotime('-1 day');
        } else {
            $from = strtotime($from);
        }
        if(empty($to)) {
            $to = $from;
        } else {
            $to = strtotime($to);
        }
        echo 'Get data from ' . date('Y-m-d',$from) . ' to ' . date('Y-m-d',$to) . PHP_EOL;
        $current_date = $from;
        if($this->connectToFtp())
        {
            $advertiser_list = Advertiser::getListYasiId();
            $type_list = [self::TYPE_CAMPAIGN => 'Campaign ID',self::TYPE_ORDER => 'Order ID', self::TYPE_CREATIVE => 'Creative ID'];
            do{
                $day = date('Y-m-d', $current_date);
                echo ' get data for ' . $day; 
                $file_name = $this->configFtp['remote_path'] . str_replace('{date}',$day,$this->configFtp['template']);
                $files = ftp_nlist($this->connectFtp,$this->configFtp['remote_path']);
                if(in_array($file_name,$files))
                {
                    echo $file_name . PHP_EOL;
                    $source_data = $this->loadFileToArray($file_name);
                    $target_data = array_combine(array_keys($type_list), array([],[],[]));
                    echo ' Skip row:';
                    //Save CSV data to $target_data array 
                    foreach($source_data as $row_data)
                    {
                        // Check if Advertiser in list
                        if(in_array($row_data['Advertiser ID'],$advertiser_list)){
                            //Add information for type data
                            foreach($type_list as $type => $name_csv)
                            {
                                if(empty($target_data[$type][$row_data[$name_csv]]))
                                {
                                    $target_data[$type][$row_data[$name_csv]] = ['source'=>$row_data, 'data'=>[]];
                                }
                                $target_data[$type][$row_data[$name_csv]]['data'][]=$row_data;
                            }
                        } else {
                            echo '.';
                        }
                    }
                    echo PHP_EOL;
                    //Save data to DB from $target_data 
                    reset($type_list);
                    foreach($type_list as $type => $name_csv)
                    {
                        foreach($target_data[$type] as $yashi_id => $data_set)
                        {
                            switch($type){
                                case self::TYPE_CAMPAIGN:
                                    $this->saveCampaign($data_set);
                                break;
                                case self::TYPE_ORDER:
                                    $this->saveOrder($data_set);
                                break;
                                case self::TYPE_CREATIVE:
                                    $this->saveCreative($data_set);
                                break;
                            }
                        }
                    }
                } else {
                    echo 'file ' . $file_name . ' not exists.' . PHP_EOL;
                }

                // get next day
                $current_date = strtotime('+1 day',$current_date);
            }while($to >= $current_date);

//            var_export($target_data);
            return ExitCode::OK;
        }
        else
        {
            return ExitCode::UNSPECIFIED_ERROR;
        } 
    }

    protected function saveCampaign($row)
    {
        //check if data is correct.
        if(!empty($row['source']) and !empty($row['data']))
        {
            //get Campaign
            $campaign = $this->getCampaign($row['source']);
            //Get CampaignData
            $condition_find = ['campaign_id' => $campaign->campaign_id, 'log_date' => strtotime($row['source']['Date'])];
            $data = CampaignData::find()->where($condition_find)->one();
            if(empty($data))
            {
                $data = new CampaignData();
                $data->setAttributes($condition_find);
            }
            $data->setAttributes($this->getSum($row['data']));
            $data->save();
        } else {
            echo 'Empty input data.' . PHP_EOL;
        }
    }

    protected function saveOrder($row)
    {
        //check if data is correct.
        if(!empty($row['source']) and !empty($row['data']))
        {
            //get Campaign
            $order = $this->getOrder($row['source']);
            //Get OrderData
            $condition_find = ['order_id' => $order->order_id, 'log_date' => strtotime($row['source']['Date'])];
            $data = OrderData::find()->where($condition_find)->one();
            if(empty($data))
            {
                $data = new OrderData();
                $data->setAttributes($condition_find);
            }
            $data->setAttributes($this->getSum($row['data']));
            $data->save();
        } else {
            echo 'Empty input data.' . PHP_EOL;
        }
    }

    protected function saveCreative($row)
    {
        //check if data is correct.
        if(!empty($row['source']) and !empty($row['data']))
        {
            //get Creative
            $creative = $this->getCreative($row['source']);
            //Get CreativeData
            $condition_find = ['creative_id' => $creative->creative_id, 'log_date' => strtotime($row['source']['Date'])];
            $data = CreativeData::find()->where($condition_find)->one();
            if(empty($data))
            {
                $data = new CreativeData();
                $data->setAttributes($condition_find);
            }
            $data->setAttributes($this->getSum($row['data']));
            $data->save();
        } else {
            echo 'Empty input data.' . PHP_EOL;
        }
    }

    protected function getCampaign($source)
    {
        $campaign = Campaign::find()->where(['yashi_campaign_id'=> $source['Campaign ID']])->one();
        if(empty($campaign))
        {
            //Creata new Campaign
            $campaign = new Campaign();
            foreach($this->mapCampaign as $field_name => $name_csv)
            {
                $campaign->{$field_name} = $source[$name_csv];
            }
            $campaign->save();
        }
        return $campaign;
    }

    protected function getOrder($source)
    {
        $order = Order::find()->where(['yashi_order_id'=> $source['Order ID']])->one();
        if(empty($order))
        {
            //Creata new Order
            $campaign = $this->getCampaign($source);
            $order = new Order();
            $order->campaign_id = $campaign->campaign_id;
            foreach($this->mapOrder as $field_name => $name_csv)
            {
                $order->{$field_name} = $source[$name_csv];
            }
            $order->save();
        }
        return $order;
    }

    protected function getCreative($source)
    {
        $creative = Creative::find()->where(['yashi_creative_id'=> $source['Creative ID']])->one();
        if(empty($creative))
        {
            //Creata new Creative
            $creative = new Creative();
            $order = $this->getOrder($source);
            $creative->order_id = $order->order_id;
            foreach($this->mapCreative as $field_name => $name_csv)
            {
                $creative->{$field_name} = $source[$name_csv];
            }
            $creative->save();
        }
        return $creative;
    }

    protected function getSum($rows)
    {
        $total = array_fill_keys(array_keys($this->mapCount),0);
        foreach($rows as $row)
        {
            foreach($total as $key => $value)
            {
                $total[$key] += (int) $row[$this->mapCount[$key]];
            }
            reset($total);
        }
        return $total;
    }

    protected function setConfigFtp()
    {
        $this->configFtp = Yii::$app->params['ftp'];
        if( empty($this->configFtp) 
            or empty($this->configFtp['host']) 
            or empty($this->configFtp['user']) 
            or empty($this->configFtp['pass'])
            or empty($this->configFtp['template']) )
        {
            $this->configFtp = [];
            echo 'Check pls config/params.php section "ftp" you need to have "host", "user", "pass" and "template"' . PHP_EOL;
            return ExitCode::CONFIG;
        }
    }
    protected function connectToFtp()
    {
        $this->setConfigFtp();
        if(!empty($this->configFtp))
        {
            if($this->connectFtp === 0)
            {
                $this->connectFtp =  ftp_connect($this->configFtp['host']);
                $login_ftp = ftp_login($this->connectFtp, $this->configFtp['user'], $this->configFtp['pass']);
                if(!$this->connectFtp or !$login_ftp)
                {
                    echo 'FTP connection has failed!' . PHP_EOL;
                    $this->connectFtp = 0;
                }
                else
                {
                    echo 'Connected to: ' . $this->configFtp['host'] . ', for user: ' . $this->configFtp['user'] . PHP_EOL;
                }
            }   
        }
        return $this->connectFtp;
    } 
    
    protected function loadFileToArray($file_name)
    {
        $data =[];
        $local_file = Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'tmp';
        $ftp = $this->connectToFtp();
        if($ftp)
        {
            $is_load = ftp_get($ftp,$local_file,$file_name,FTP_BINARY);
            if($is_load)
            {
                echo 'File: ' . $file_name . ' was download from ftp.' . PHP_EOL;
                $content_csv = file_get_contents($local_file);
                $current_eol = PHP_EOL;
                $count_windows_eol = substr_count($content_csv, self::WINDOWS_EOL);
                $count_linux_eol   = substr_count($content_csv, self::LINUX_EOL);
                if($count_windows_eol > 0 )
                {
                    $current_eol = self::WINDOWS_EOL;
                } 
                elseif ($count_linux_eol > 0)
                {
                    $current_eol = self::LINUX_EOL;
                }
                echo 'WINDOWS_EOL: ' . $count_windows_eol . ' LINUX_EOL: '. $count_linux_eol . PHP_EOL;
                $csv_lines = explode($current_eol, $content_csv );
                $header = [];
                foreach($csv_lines as $line)
                {
                    $tmp = str_getcsv($line);
                    if(empty($header))
                    {
                        $header = $tmp;
                    }
                    else
                    {
                        if(count($header) === count($tmp))
                        {
                            $data[] = array_combine($header,$tmp);
                        }
                        else
                        {
                            if(count($tmp)>1)
                            {
                                echo 'WARNING: header = ' . var_export($header,true) . ' value = ' . var_export($tmp,true);
                            }
                        } 
                    }
                }
            }
            else
            {
                echo 'File: ' . $file_name . ' was not download from ftp.' . PHP_EOL;
            }
        }
        return $data;
    }
    public function __destruct()
    {
        if($this->connectFtp !== 0)
        {
            echo 'Close connect to FTP.' . PHP_EOL;
            ftp_close($this->connectFtp);
        }
    }
}
