<?

namespace mrssoft\mdash;

use Yii;
use EMTypograph;
use yii\base\Object;;

/**
 * Типограф Е.Муравьёва
 * http://mdash.ru/
 *
 * Class Mdash
 * @package mrssoft\mdash
 */
class Mdash extends Object
{
    /**
     * Опиции типографа
     * @var array
     */
    public $options = [];

    /**
     * Удалённое или локльное выполнение
     * @var bool
     */
    public $remote = false;

    /**
     * Адрес API
     * @var string
     */
    public $apiUrl = 'http://mdash.ru/api.v1.php';

    /**
     * Запустить типограф на выполнение
     * @param string $text
     * @return string
     */
    public function process($text)
    {
        $text = trim($text);
        if (empty($text)) {
            return '';
        }
        
        if ($this->remote) {
            return $this->request($text);
        } else {
            return $this->local($text);
        }
    }

    /**
     * Локально
     * @param string $text
     * @return string
     */
    private function local($text)
    {
        require_once(__DIR__ . '/EMT.php');

        $typograph = new EMTypograph();
        $typograph->setup($this->options);
        $typograph->set_text($text);
        return $typograph->apply();
    }

    /**
     * Удалённо
     * @param string $text
     * @return string
     */
    private function request($text)
    {
        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_PROXY => false,
            CURLOPT_CONNECTTIMEOUT => 20,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => array_merge($this->options, ['text' => $text])
        ]);
        $result = curl_exec($ch);
        
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200 || curl_errno($ch) || empty($result)) {
            Yii::warning('Mdash error: (' . curl_errno($ch) . ') ' . curl_error($ch) . ', response: ' . $result);
            $result = $text;
        }
        curl_close($ch);
        
        $result = @json_decode($result, true);
        if (!is_array($result) || !array_key_exists('result', $result)) {
            $result = $text;
        } else {
            $result = $result['result'];
        }

        return $result;
    }
}