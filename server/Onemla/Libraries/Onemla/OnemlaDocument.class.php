<?php
/**
 * Created by PhpStorm.
 * User: Jery
 * Date: 2015/4/21
 * Time: 12:15
 */

namespace Onemla;

class OnemlaDocument{

    /**
     * Document title
     *
     * @var    string
     */
    public $title = '传芯';

    /**
     * Document description
     *
     * @var    string
     */
    public $description = '';

    /**
     * Document full URL
     *
     * @var    string
     */
    public $link = '';

    /**
     * Document generator
     *
     * @var    string
     */
    public $_generator = '传芯';

    /**
     * Document modified date
     *
     * @var    string
     */
    public $_mdate = '';

    /**
     * Tab string
     *
     * @var    string
     */
    public $_tab = "\11";

    /**
     * Contains the line end string
     *
     * @var    string
     */
    public $_lineEnd = "\12";

    /**
     * Contains the character encoding string
     *
     * @var    string
     */
    public $_charset = 'utf-8';

    /**
     * Document mime type
     *
     * @var    string
     */
    public $_mime = '';

    /**
     * Array of linked scripts
     *
     * @var    array
     */
    public $_scripts = array();
    /**
     * Array of scripts placed in the header
     *
     * @var    array
     */
    public $_script = array();
    /**
     * Array of linked style sheets
     *
     * @var    array
     */
    public $_styleSheets = array();

    /**
     * Array of included style declarations
     *
     * @var    array
     */
    public $_style = array();

    /**
     * Array of included keywords
     *
     * @var    array
     */
    public $_keywords = array();
    /**
     * Media version added to assets
     *
     * @var    string
     */
    protected $mediaVersion = null;

    /**
     * Class constructor.
     *
     * @param   array  $options  Associative array of options
     */
    public function __construct($options = array()){
        if (array_key_exists('lineend', $options))
        {
            $this->setLineEnd($options['lineend']);
        }

        if (array_key_exists('charset', $options))
        {
            $this->setCharset($options['charset']);
        }

        if (array_key_exists('link', $options))
        {
            $this->setLink($options['link']);
        }

        if (array_key_exists('mediaversion', $options))
        {
            $this->setMediaVersion($options['mediaversion']);
        }
    }

    /**
     * Adds a linked script to the page
     *
     * @param   string   $url    URL to the linked script
     * @param   string   $type   Type of script. Defaults to 'text/javascript'
     * @param   boolean  $defer  Adds the defer attribute.
     * @param   boolean  $async  Adds the async attribute.
     * @return Onemla\OnemlaDocument
     */
    public function addScript($url, $type = "text/javascript", $defer = false, $async = false){

        $this->_scripts[$url]['mime'] = $type;
        $this->_scripts[$url]['defer'] = $defer;
        $this->_scripts[$url]['async'] = $async;

        return $this;
    }

    /**
     * Adds a linked script to the page with a version to allow to flush it. Ex: myscript.js54771616b5bceae9df03c6173babf11d
     * If not specified Joomla! automatically handles versioning
     *
     * @param   string   $url      URL to the linked script
     * @param   string   $version  Version of the script
     * @param   string   $type     Type of script. Defaults to 'text/javascript'
     * @param   boolean  $defer    Adds the defer attribute.
     * @param   boolean  $async    [description]
     * @return Onemla\OnemlaDocument
     */
    public function addScriptVersion($url, $version = null, $type = "text/javascript", $defer = false, $async = false){
        // Automatic version
        if ($version === null)
        {
            $version = $this->getMediaVersion();
        }

        if (!empty($version) && strpos($url, '?') === false)
        {
            $url .= '?' . $version;
        }

        return $this->addScript($url, $type, $defer, $async);
    }

    /**
     * Adds a script to the page
     *
     * @param   string  $content  Script
     * @param   string  $type     Scripting mime (defaults to 'text/javascript')
     * @return Onemla\OnemlaDocument
     */
    public function addScriptDeclaration($content, $type = 'text/javascript'){
        if (!isset($this->_script[strtolower($type)]))
        {
            $this->_script[strtolower($type)] = $content;
        }
        else
        {
            $this->_script[strtolower($type)] .= chr(13) . $content;
        }

        return $this;
    }

    /**
     * Adds a linked stylesheet to the page
     *
     * @param   string  $url      URL to the linked style sheet
     * @param   string  $type     Mime encoding type
     * @param   string  $media    Media type that this stylesheet applies to
     * @param   array   $attribs  Array of attributes
     * @return Onemla\OnemlaDocument
     */
    public function addStyleSheet($url, $type = 'text/css', $media = null, $attribs = array()){
        $this->_styleSheets[$url]['mime'] = $type;
        $this->_styleSheets[$url]['media'] = $media;
        $this->_styleSheets[$url]['attribs'] = $attribs;

        return $this;
    }

    /**
     * Adds a linked stylesheet version to the page. Ex: template.css?54771616b5bceae9df03c6173babf11d
     * If not specified Joomla! automatically handles versioning
     *
     * @param   string  $url      URL to the linked style sheet
     * @param   string  $version  Version of the stylesheet
     * @param   string  $type     Mime encoding type
     * @param   string  $media    Media type that this stylesheet applies to
     * @param   array   $attribs  Array of attributes
     * @return Onemla\OnemlaDocument
     */
    public function addStyleSheetVersion($url, $version = null, $type = "text/css", $media = null, $attribs = array()){
        // Automatic version
        if ($version === null)
        {
            $version = $this->getMediaVersion();
        }

        if (!empty($version) && strpos($url, '?') === false)
        {
            $url .= '?' . $version;
        }

        return $this->addStyleSheet($url, $type, $media, $attribs);
    }

    /**
     * Adds a stylesheet declaration to the page
     *
     * @param   string  $content  Style declarations
     * @param   string  $type     Type of stylesheet (defaults to 'text/css')
     * @return Onemla\OnemlaDocument
     */
    public function addStyleDeclaration($content, $type = 'text/css'){
        if (!isset($this->_style[strtolower($type)]))
        {
            $this->_style[strtolower($type)] = $content;
        }
        else
        {
            $this->_style[strtolower($type)] .= chr(13) . $content;
        }

        return $this;
    }

    /**
     * Sets the document charset
     *
     * @param   string  $type  Charset encoding string
     *
     * @return Onemla\OnemlaDocument
     */
    public function setCharset($type = 'utf-8'){
        $this->_charset = $type;

        return $this;
    }

    /**
     * Returns the document charset encoding.
     *
     * @return  string
     */
    public function getCharset(){
        return $this->_charset;
    }

    /**
     * Sets the string used to indent HTML
     *
     * @param   string  $string  String used to indent ("\11", "\t", '  ', etc.).
     *
     * @return Onemla\OnemlaDocument
     */
    public function setTab($string)
    {
        $this->_tab = $string;

        return $this;
    }

    /**
     * Returns a string containing the unit for indenting HTML
     *
     * @return  string
     */
    public function _getTab()
    {
        return $this->_tab;
    }

    /**
     * Sets the string used to indent HTML
     *
     * @param   string  $keyword  keyword
     *
     * @return Onemla\OnemlaDocument
     */
    public function addKeyword($keyword)
    {
        $this->_keywords[] = $keyword;

        return $this;
    }

    /**
     * Returns a array keywords
     *
     * @return  array
     */
    public function _getKeywors()
    {
        return $this->_keywords;
    }

    /**
     * Sets the title of the document
     *
     * @param   string  $title  The title to be set
     *
     * @return Onemla\OnemlaDocument
     */
    public function setTitle($title){
        $this->title = $title;

        return $this;
    }

    /**
     * Return the title of the document.
     *
     * @return  string
     */
    public function getTitle(){
        return $this->title;
    }

    /**
     * Set the assets version
     *
     * @param   string  $mediaVersion  Media version to use
     *
     * @return Onemla\OnemlaDocument
     */
    public function setMediaVersion($mediaVersion){
        $this->mediaVersion = strtolower($mediaVersion);

        return $this;
    }

    /**
     * Return the media version
     *
     * @return  string
     */
    public function getMediaVersion(){
        return $this->mediaVersion;
    }


    /**
     * Sets the description of the document
     *
     * @param   string  $description  The description to set
     *
     * @return Onemla\OnemlaDocument
     */
    public function setDescription($description){
        $this->description = $description;

        return $this;
    }

    /**
     * Return the title of the page.
     *
     * @return  string
     */
    public function getDescription(){
        return $this->description;
    }

    /**
     * Sets the document link
     *
     * @param   string  $url  A url
     *
     * @return Onemla\OnemlaDocument
     */
    public function setLink($url){
        $this->link = $url;

        return $this;
    }

    /**
     * Returns the document base url
     *
     * @return string
     */
    public function getLink(){
        return $this->link;
    }

    /**
     * Sets the document generator
     *
     * @param   string  $generator  The generator to be set
     *
     * @return Onemla\OnemlaDocument
     */
    public function setGenerator($generator){
        $this->_generator = $generator;

        return $this;
    }

    /**
     * Returns the document generator
     *
     * @return  string
     */
    public function getGenerator(){
        return $this->_generator;
    }

    /**
     * Sets the document modified date
     *
     * @param   string  $date  The date to be set
     *
     * @return Onemla\OnemlaDocument
     */
    public function setModifiedDate($date){
        $this->_mdate = $date;

        return $this;
    }

    /**
     * Returns the document modified date
     *
     * @return  string
     */
    public function getModifiedDate(){
        return $this->_mdate;
    }


    /**
     * Return the document MIME encoding that is sent to the browser.
     *
     * @return  string
     */
    public function getMimeEncoding(){
        return $this->_mime;
    }

    /**
     * Sets the line end style to Windows, Mac, Unix or a custom string.
     *
     * @param   string  $style  "win", "mac", "unix" or custom string.
     *
     * @return Onemla\OnemlaDocument
     */
    public function setLineEnd($style){
        switch ($style)
        {
            case 'win':
                $this->_lineEnd = "\15\12";
                break;
            case 'unix':
                $this->_lineEnd = "\12";
                break;
            case 'mac':
                $this->_lineEnd = "\15";
                break;
            default:
                $this->_lineEnd = $style;
        }

        return $this;
    }

    /**
     * Returns the lineEnd
     *
     * @return  string
     */
    public function _getLineEnd(){
        return $this->_lineEnd;
    }

    /**
     * Generates the head HTML and return the results as a string
     *
     * @return  string  The head hTML
     *
     * @since   11.1
     */
    public function fetchHead()
    {
        // Get line endings
        $lnEnd = $this->_getLineEnd();
        $tagEnd = ' />';
        $tab = $this->_getTab();
        $buffer = '';

        // Generate charset when using HTML5 (should happen first)
        $buffer .= $tab.'<meta charset="' . $this->getCharset() . '" />' . $lnEnd;

        $keyWords = $this->_keywords;
        if(count($keyWords)){
            $buffer .= $tab . '<meta name="keywords" content="' . htmlspecialchars(join(',', $keyWords)) . '" />' . $lnEnd;
        }

        // Don't add empty descriptions
        $documentDescription = $this->getDescription();

        if ($documentDescription)
        {
            $buffer .= $tab . '<meta name="description" content="' . htmlspecialchars($documentDescription) . '" />' . $lnEnd;
        }

        // Don't add empty generators
        $generator = $this->getGenerator();

        if ($generator)
        {
            $buffer .= $tab . '<meta name="generator" content="' . htmlspecialchars($generator) . '" />' . $lnEnd;
        }

        $buffer .= $tab . '<title>' . htmlspecialchars($this->getTitle(), ENT_COMPAT, 'UTF-8') . '</title>' . $lnEnd;

        // Generate link declarations
        foreach ($this->_links as $link => $linkAtrr)
        {
            $buffer .= $tab . '<link href="' . $link . '" ' . $linkAtrr['relType'] . '="' . $linkAtrr['relation'] . '"';

            $buffer .= ' />' . $lnEnd;
        }

        // Generate stylesheet links
        foreach ($this->_styleSheets as $strSrc => $strAttr)
        {
            $buffer .= $tab . '<link rel="stylesheet" href="' . $strSrc . '"';
            $buffer .= ' type="' . $strAttr['mime'] . '"';

            if (!is_null($strAttr['media']))
            {
                $buffer .= ' media="' . $strAttr['media'] . '"';
            }

            $buffer .= $tagEnd . $lnEnd;
        }

        // Generate stylesheet declarations
        foreach ($this->_style as $type => $content)
        {
            $buffer .= $tab . '<style type="' . $type . '">' . $lnEnd;

            // This is for full XHTML support.
            if ($this->_mime != 'text/html')
            {
                $buffer .= $tab . $tab . '/*<![CDATA[*/' . $lnEnd;
            }

            $buffer .= $content . $lnEnd;

            // See above note
            if ($this->_mime != 'text/html')
            {
                $buffer .= $tab . $tab . '/*]]>*/' . $lnEnd;
            }

            $buffer .= $tab . '</style>' . $lnEnd;
        }

        // Generate script file links
        foreach ($this->_scripts as $strSrc => $strAttr)
        {
            $buffer .= $tab . '<script src="' . $strSrc . '"';
            $defaultMimes = array(
                'text/javascript', 'application/javascript', 'text/x-javascript', 'application/x-javascript'
            );
            $buffer .= ' type="' . $strAttr['mime'] . '"';

            if ($strAttr['defer'])
            {
                $buffer .= ' defer="defer"';
            }

            if ($strAttr['async'])
            {
                $buffer .= ' async="async"';
            }

            $buffer .= '></script>' . $lnEnd;
        }

        // Generate script declarations
        foreach ($this->_script as $type => $content)
        {
            $buffer .= $tab . '<script type="' . $type . '">' . $lnEnd;

            // This is for full XHTML support.
            if ($this->_mime != 'text/html')
            {
                $buffer .= $tab . $tab . '//<![CDATA[' . $lnEnd;
            }

            $buffer .= $content . $lnEnd;

            // See above note
            if ($this->_mime != 'text/html')
            {
                $buffer .= $tab . $tab . '//]]>' . $lnEnd;
            }

            $buffer .= $tab . '</script>' . $lnEnd;
        }

        return $buffer;
    }

    /**
     * 检查表单Token
     *
     * @param   string  $method  The request method in which to look for the token key.
     *
     * @return  boolean  True if found and valid, false otherwise.
     */
    public function checkFormToken($method = 'post'){
        $token = $this->getFormToken();

        if (!OnemlaRequest::getInt($token,0, $method) ) {
           return false;
        } else {
            return true;
        }
    }

    /**
     * Method to determine a hash for anti-spoofing variable names
     *
     * @param   boolean  $forceNew  If true, force a new token to be created
     *
     * @return  string  Hashed var name
     */
    public function getFormToken($forceNew = false){
        $user    = OnemlaHelper::getUser();

        $hash = md5(C('TOKEN_SECRET') .$user->id . $this->getToken($forceNew));

        return $hash;
    }

    /**
     * Get a session token, if a token isn't set yet one will be generated.
     *
     * Tokens are used to secure forms from spamming attacks. Once a token
     * has been generated the system will check the post request to see if
     * it is present, if not it will invalidate the session.
     *
     * @param   boolean  $forceNew  If true, force a new token to be created
     *
     * @return  string  The session token
     */
    public function getToken($forceNew = false){
        $token = OnemlaHelper::session('sessionToken');

        // Create a token
        if ($token === null || $forceNew) {
            $token = $this->_createToken(12);
            OnemlaHelper::session('sessionToken', $token);
        }

        return $token;
    }

    /**
     * Create a token-string
     *
     * @param   integer  $length  Length of string
     *
     * @return  string  Generated token
     */
    protected function _createToken($length = 32){
        static $chars = '0123456789abcdef';
        $max = strlen($chars) - 1;
        $token = '';
        $name = session_name();

        for ($i = 0; $i < $length; ++$i)
        {
            $token .= $chars[(rand(0, $max))];
        }

        return md5($token . $name);
    }
}