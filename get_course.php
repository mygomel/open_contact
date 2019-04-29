<?php
/**
 * Created by Maus 29.04.2019 22:39 mygomel@gmail.com
 */


/**
 * Class API gets currency rate ratio
 * To run, use 'php get_course.php --from=USD --to=EUR'
 */
class API
{
    const URL = 'http://www.nbrb.by/API/ExRates/Rates?Periodicity=0';
    protected $_GET, $from, $to;

    public function __construct(array $arg)
    {
        $_GET = getopt(null, $arg);

        if (!isset($_GET['to'])) {
            exit('Parameter --to not written in console');
        }

        if (!isset($_GET['from'])) {
            $_GET['from'] = 'BYN';
        }

        $this->_GET = $_GET;
    }

    public function get_object()
    {
        $response = file_get_contents(self::URL);
        return json_decode($response);
    }

    public function set_rate()
    {
        $array = $this->get_object();
        foreach ($array as $currency) {
            if ($currency->Cur_Abbreviation == $_GET['from']) {
                $this->from = $currency->Cur_OfficialRate;
            }

            if ($currency->Cur_Abbreviation == $_GET['to']) {
                $this->to = $currency->Cur_OfficialRate;
            }
        }

        if ($_GET['from'] == 'BYN') {
            $this->from = 1;
        }

        if ($_GET['to'] == 'BYN') {
            $this->to = 1;
        }

        if (!$this->to OR !$this->from) {
            exit('No currencies found');
        }
    }

    public function show()
    {
        $this->set_rate();

        $ratio = round($this->to / $this->from, 2);

        return $ratio;
    }
}


$api = new API(['from:', 'to:']);

print $api->show();

