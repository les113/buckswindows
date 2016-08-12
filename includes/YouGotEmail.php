<?php ob_start();
/**
 */
/* "You Got Email", a PHP-FormToEmail: Script to send an Email From Data submitted on Form with an Attachments*/
/* ========================================================================================*/
/* Copyright (c) 2003 Chirag Ahmedabadi<chirag_kansara@yahoo.com>							*/
/* http://www.indianic.com, http://www.php-india.net                    					*/
/* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
# THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
# OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
# ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
# OTHER DEALINGS IN THE SOFTWARE.
/* This script uses the PHP email class by   Brent R. Matzelle as given belwo				*/
// phpmailer - PHP email class
// Version 1.65, Created 08/09/2002
// Class for sending email using either
// sendmail, PHP mail(), or SMTP.  Methods are
// based upon the standard AspEmail(tm) classes.
// Author: Brent R. Matzelle <bmatzelle@yahoo.com>
// License: LGPL, see LICENSE
/*                                                                      				*/
/* This program is free software. You can redistribute it and/or modify 				*/
/* it under the terms of the GNU General Public License as published by 				*/
/* the Free Software Foundation; either version 2 of the License.       				*/
// / //////////////////////////////////////////////////
// phpmailer - PHP email class
// Version 1.65, Created 08/09/2002
// Class for sending email using either
// sendmail, PHP mail(), or SMTP.  Methods are
// based upon the standard AspEmail(tm) classes.
// Author: Brent R. Matzelle <bmatzelle@yahoo.com>
// License: LGPL, see LICENSE
// //////////////////////////////////////////////////
$SendAddress = "enquiry@buckswindows.com"; //YOUR EMAIL ADDRESS, where you want to receive the email
$mail_subject = "Bucks Windows Consultation Request"; //Subject for email that you get
// Following is the heading that you would receive with this message in your email within the body
$MyContents = "You have an enquiry from buckswindows.co.uk"; // if you wish to put an extra content in email, aprat from what is submited by user
$IgnoreFields[] = "SEND_BUTTON"; //Add list of fields, variables, here separated by comma,to ignore/avoid them in sending by email 
$PutPostData = 1; // set to 1,if want to receive the posted data in your email, if form action method is "POST"
$PutGetData = 0; // set to 1,if want to receive the get data in your email, if form action method is "GET"
$PutEnvData = 0; //set to 1, if you wish to receive environment data like, HOSTNAME,MACHTYPE,SHELL etc about the server on which you are running this script  
$PutServerData = 0; //set to 1, if you wish to receive data of client machine like, REMOTE_ADDR,HTTP_USER_AGENT,REMOTE_PORT,etc  
$PutCookieData = 0; //set to 1, if you wish to receive Cookies from client machine 
$PutAllData = 0; //set to 1, if you wish to receive all data like posted, server, environment,Cookies etc, this would incluce all data in your email, do not prefer to use this if you are not sure on what you are going to get.
$isThereAttachment = 0; //set 0 if no attachment is required
// if you have file upload option on your form, you must provide absolutes path to some temp folder with chmod 777 on your  server where this script is lying to keep the upload file temporary before sending email, after sending email it would be deleted, keep start and trailing slash
$TempFolder = "";
// Parameter for upload validation,
$my_max_file_size = "10000"; // # in bytes, for any type of file upload
// you can added as many as allowed types here by comma, for example if you are going to allow upload Ms Office file, you can add like application/msword,application/x-excel etc
$allowed_types = array("image/bmp", "image/gif", "image/png", "image/jpeg");
$image_max_width = "1400"; //this validates height of image in case of image upload
$image_max_height = "1400"; //this validates width of image in case of image upload
$DeleteUploadFiles = "0"; // Do you wish to delete the uploaded files or wish to keep on server?.If you wish to keep the file set to 1 or set to 0
// If you wish to send cc to some other email addresses, give here seperated by comma
$SendCCTo[] = "";
// if you wish to send BCC to some other user, add list by comma separated
$SendBCCTo[] = "";
// Following is the subject of email that you would receive
$EmailFromAddress = "";
// Following is from anme for email sent
$EmailFromName = "";
// following is the reply address that you wish to have in your send email
$ReplyToAddress = "";
// Following is the reply name for email sent
$ReplyToName = "";
// Set following variable to 1, if you wish to receive email in html format, prefer this.
$isHTMLMail = 1; //set 0 for text email
// Following variable sets where your user will be sent after sending an email, give complete url if page is located on other folder or on another server, IF EMAIL IS SENT SUCESSFULLY
$SendUserToSucess = "../enquiry-thanks.php"; //it can be like "http://mydomain.com/Thanks.html"
// Following variable sets where your user will be sent after sending an email, give complete url if page is located on other folder or on another server, IF WE CANT SENT DATA BY EMAIL
$SendUserToFail = "../enquiry-fail.php"; //it can be like "http://mydomain.com/fail.html"
// Code to Format Your Data which is going to be send by an email, if you can, edit it
$TableStart = "<table align='center' border='1'><Tr align='center'><td  colspan='4' align='center'><strong><HEADING></strong></td></TR>";
$DataLoop = "<td><B><var_name>:-<b></td><td><var_value></td>";
$TableEnd = "</table><BR><P>";
// //////////////////////////////////////////////////
// Function to validate uploded file
function validate_upload($the_file)
{
    global $my_max_file_size, $image_max_width, $image_max_height, $allowed_types, $the_file_type, $registered_types;

    if (isset($_FILES[$the_file]["name"]))
    { 
        // if file size is greater than max file size defined
        if ($_FILES[$the_file]['size'] > $my_max_file_size) return false; 
        // if file type is not allowed
        if (!in_array($_FILES[$the_file]['type'], $allowed_types)) return false; 
        // if file uploaded is images then validate the height and widht of the file
        if (ereg("image", $_FILES[$the_file]['type']) && (in_array($_FILES[$the_file]['type'], $allowed_types)))
        {
            $size = GetImageSize($_FILES[$the_file]['tmp_name']);
            list($foo, $width, $bar, $height) = explode("\"", $size[3]);
            if ($width > $image_max_width) return false;
            if ($height > $image_max_height)return false;
        } 
        return true;
    } 
} 
// End of validate upload function
// fuction validate email address
function validate_email($email)
{
    $email = trim($email); # removes white space 
    if (!empty($email)): 
        // validate email address syntax
        if (preg_match('/^[a-z0-9\_\.]+@[a-z0-9\-]+\.[a-z]+\.?[a-z]{2,4}$/i', $email, $match)):
            return strtolower($match[0]); # valid! 
        endif;
        endif;
        return false; # NOT valid! 
    } 
    // end of function to validate email address
    // Function to validate and format the variables available
    function CheckNFormat($var, $value)
    {
        Global $IgnoreFields, $DataLoop; 
        // if varibale is not in the ignore list
        if (!in_array($var, $IgnoreFields))
        { 
            // format the display
            $ReplaceVarName = str_replace("<var_name>", $var, $DataLoop);
            $ReplaceVarValue = str_replace("<var_value>", $value, $DataLoop);
            $data = "<tr>" . $ReplaceVarName . $ReplaceVarValue . "</tr>";
            return $data;
        } 
        else
        {
            return false;
        } 
    } 
    // //////////////////////////////////////////////////
    function GetGlobals()
    {
        global $k , $TableStart, $DataLoop, $TableEnd ;
        $data = str_replace("<HEADING>", "All Global Data", $TableStart);
        while (list($key, $val) = each($GLOBALS))
        {
            $data .= CheckNFormat($key, $val);
        } 
        return $data;
    } 
    function GetPostData()
    {
        global $k , $TableStart, $DataLoop, $TableEnd ;
        $data = str_replace("<HEADING>", "Data Posted From Form", $TableStart);
        while (list($key, $val) = each($_POST))
        {
            $ReplaceVarName = str_replace("<var_name>", $key, $DataLoop); 
            // If data is an array like multiple list box
            if (!is_array($val))
            {
                $val1 = $val;
            } 
            else
            {
                $val1 = "";
                foreach($val as $res)
                {
                    $val1 .= $comma . $res;
                    $comma = ",&nbsp";
                } 
            } 
            $data .= CheckNFormat($key, $val1);
        } 
        return $data;
    } 
    function GetGetData()
    {
        global $k , $TableStart, $DataLoop, $TableEnd ;
        $data = str_replace("<HEADING>", "Get Data of Form", $TableStart);
        while (list($key, $val) = each($_GET))
        {
            $ReplaceVarName = str_replace("<var_name>", $key, $DataLoop); 
            // If data is an array like multiple list box
            if (!is_array($val))
            {
                $val1 = $val;
            } 
            else
            {
                $val1 = "";
                foreach($val as $res)
                {
                    $val1 .= $comma . $res;
                    $comma = ",&nbsp";
                } 
            } 
            $data .= CheckNFormat($key, $val1);
        } 
        return $data;
    } 
    function GetCookieData()
    {
        global $k , $TableStart, $DataLoop, $TableEnd ;
        $data = str_replace("<HEADING>", "Cookie Data", $TableStart);
        while (list($key, $val) = each($_COOKIE))
        {
            $ReplaceVarName = str_replace("<var_name>", $key, $DataLoop); 
            // If data is an array like multiple list box
            if (!is_array($val))
            {
                $val1 = $val;
            } 
            else
            {
                $val1 = "";
                foreach($val as $res)
                {
                    $val1 .= $comma . $res;
                    $comma = ",&nbsp";
                } 
            } 
            $data .= CheckNFormat($key, $val1);
        } 
        return $data;
    } 
    function GetServerData()
    {
        global $k , $TableStart, $DataLoop, $TableEnd ;
        $data = str_replace("<HEADING>", "Server Data", $TableStart);
        while (list($key, $val) = each($_SERVER))
        {
            $ReplaceVarName = str_replace("<var_name>", $key, $DataLoop); 
            // If data is an array like multiple list box
            if (!is_array($val))
            {
                $val1 = $val;
            } 
            else
            {
                $val1 = "";
                foreach($val as $res)
                {
                    $val1 .= $comma . $res;
                    $comma = ",&nbsp";
                } 
            } 
            $data .= CheckNFormat($key, $val1);
        } 
        return $data;
    } 
    function GetEnvData()
    {
        global $k , $TableStart, $DataLoop, $TableEnd ;
        $data = str_replace("<HEADING>", "Environment Data", $TableStart);
        while (list($key, $val) = each($_ENV))
        {
            $ReplaceVarName = str_replace("<var_name>", $key, $DataLoop); 
            // If data is an array like multiple list box
            if (!is_array($val))
            {
                $val1 = $val;
            } 
            else
            {
                $val1 = "";
                foreach($val as $res)
                {
                    $val1 .= $comma . $res;
                    $comma = ",&nbsp";
                } 
            } 
            $data .= CheckNFormat($key, $val1);
        } 
        return $data;
    } 
    // Following function process the uploaded files and adds as an attachment to email
    function ProcessFiles()
    {
        Global $mail, $tempfolderpath, $DeleteUploadFiles, $UploadedFiles;
        while (list($key, $val) = each($_FILES))
        {
            $copypath = $tempfolderpath . $_FILES[$key]["name"];
            if (is_uploaded_file($_FILES[$key]['tmp_name']))
            {
                @copy($_FILES[$key]['tmp_name'], $copypath);
                if (validate_upload($key))
                {
                    $mail->AddAttachment($copypath);
                    if ($DeleteUploadFiles == '0')
                    {
                        array_push($UploadedFiles, $copypath);
                    } 
                } 
            } 
        } 
        // following function deletes the uploaded file if delete option is set
        function DeleteFiles()
        {
            Global $tempfolderpath, $UploadedFiles;
            foreach($UploadedFiles as $res)
            {
                @unlink($res);
            } 
            return true;
        } 
    } 
    // PLEASE DON'T CHANGE ANYTHING BELOW
    $UploadedFiles = array();
    class phpmailer
    { 
        // ///////////////////////////////////////////////
        // PUBLIC VARIABLES
        // ///////////////////////////////////////////////
        /**
         * Email priority (1 = High, 3 = Normal, 5 = low).
         * 
         * @access public 
         * @var int 
         */
        var $Priority = 3;
        /**
         * Sets the CharSet of the message.
         * 
         * @access public 
         * @var string 
         */
        var $CharSet = "iso-8859-1";
        /**
         * Sets the Content-type of the message.
         * 
         * @access public 
         * @var string 
         */
        var $ContentType = "text/plain";
        /**
         * Sets the Encoding of the message. Options for this are "8bit",
         * "7bit", "binary", "base64", and "quoted-printable".
         * 
         * @access public 
         * @var string 
         */
        var $Encoding = "8bit";
        /**
         * Holds the most recent mailer error message.
         * 
         * @access public 
         * @var string 
         */
        var $ErrorInfo = "";
        /**
         * Sets the From email address for the message.
         * 
         * @access public 
         * @var string 
         */
        var $From = "root@localhost";
        /**
         * Sets the From name of the message.
         * 
         * @access public 
         * @var string 
         */
        var $FromName = "Root User";
        /**
         * Sets the Sender email of the message. If not empty, will be sent via -f to sendmail
         * or as 'MAIL FROM' in smtp mode.
         * 
         * @access public 
         * @var string 
         */
        var $Sender = "";
        /**
         * Sets the Subject of the message.
         * 
         * @access public 
         * @var string 
         */
        var $Subject = "";
        /**
         * Sets the Body of the message.  This can be either an HTML or text body.
         * If HTML then run IsHTML(true).
         * 
         * @access public 
         * @var string 
         */
        var $Body = "";
        /**
         * Sets the text-only body of the message.  This automatically sets the
         * email to multipart/alternative.  This body can be read by mail
         * clients that do not have HTML email capability such as mutt. Clients
         * that can read HTML will view the normal Body.
         * 
         * @access public 
         * @var string 
         */
        var $AltBody = "";
        /**
         * Sets word wrapping on the body of the message to a given number of 
         * characters.
         * 
         * @access public 
         * @var int 
         */
        var $WordWrap = 0;
        /**
         * Method to send mail: ("mail", "sendmail", or "smtp").
         * 
         * @access public 
         * @var string 
         */
        var $Mailer = "mail";
        /**
         * Sets the path of the sendmail program.
         * 
         * @access public 
         * @var string 
         */
        var $Sendmail = "";
        /**
         * Turns Microsoft mail client headers on and off.  Useful mostly
         *     for older clients.
         * 
         * @access public 
         * @var bool 
         */
        var $UseMSMailHeaders = false;
        /**
         * Path to phpmailer plugins.  This is now only useful if the SMTP class 
         * is in a different directory than the PHP include path.
         * 
         * @access public 
         * @var string 
         */
        var $PluginDir = "";
        /**
         * Holds phpmailer version.
         * 
         * @access public 
         * @var string 
         */
        var $Version = "1.65";
        /**
         * Sets the email address that a reading confirmation will be sent.
         * 
         * @access public 
         * @var string 
         */
        var $ConfirmReadingTo = "";
        /**
         * Sets the line endings of the message.
         * 
         * @access public 
         * @var string 
         */
        var $LE = "\n";
        /**
         * Sets the hostname to use in Message-Id and Received headers
         *     and as default HELO string. If empty, the value returned
         *     by SERVER_NAME is used or 'localhost.localdomain'.
         * 
         * @access public 
         * @var string 
         */
        var $Hostname = ""; 
        // ///////////////////////////////////////////////
        // SMTP VARIABLES
        // ///////////////////////////////////////////////
        /**
         * Sets the SMTP hosts.  All hosts must be separated by a
         *     semicolon.  You can also specify a different port
         *     for each host by using this format: [hostname:port]
         *     (e.g. "smtp1.example.com:25;smtp2.example.com").
         *     Hosts will be tried in order.
         * 
         * @access public 
         * @var string 
         */
        var $Host = "localhost";
        /**
         * Sets the default SMTP server port.
         * 
         * @access public 
         * @var int 
         */
        var $Port = 25;
        /**
         * Sets the SMTP HELO of the message (Default is $Hostname).
         * 
         * @access public 
         * @var string 
         */
        var $Helo = "";
        /**
         * Sets SMTP authentication. Utilizes the Username and Password variables.
         * 
         * @access public 
         * @var bool 
         */
        var $SMTPAuth = false;
        /**
         * Sets SMTP username.
         * 
         * @access public 
         * @var string 
         */
        var $Username = "";
        /**
         * Sets SMTP password.
         * 
         * @access public 
         * @var string 
         */
        var $Password = "";
        /**
         * Sets the SMTP server timeout in seconds. This function will not 
         *     work with the win32 version.
         * 
         * @access public 
         * @var int 
         */
        var $Timeout = 10;
        /**
         * Sets SMTP class debugging on or off.
         * 
         * @access public 
         * @var bool 
         */
        var $SMTPDebug = false; 
        // ///////////////////////////////////////////////
        // PRIVATE VARIABLES
        // ///////////////////////////////////////////////
        /**
         * Holds all "To" addresses.
         * 
         * @access private 
         * @var array 
         */
        var $to = array();

        /**
         * Holds all "CC" addresses.
         * 
         * @access private 
         * @var array 
         */
        var $cc = array();
        /**
         * Holds all "BCC" addresses.
         * 
         * @access private 
         * @var array 
         */
        var $bcc = array();
        /**
         * Holds all "Reply-To" addresses.
         * 
         * @var array 
         */
        var $ReplyTo = array();
        /**
         * Holds all string and binary attachments.
         * 
         * @access private 
         * @var array 
         */
        var $attachment = array();
        /**
         * Holds all custom headers.
         * 
         * @var array 
         */
        var $CustomHeader = array();
        /**
         * Holds the type of the message.
         * 
         * @var string 
         */
        var $message_type = "";
        /**
         * Holds the message boundaries.
         * 
         * @access private 
         * @var string array
         */
        var $boundary = array(); 
        // ///////////////////////////////////////////////
        // VARIABLE METHODS
        // ///////////////////////////////////////////////
        /**
         * Sets message type to HTML.  Returns void.
         * 
         * @access public 
         * @return void 
         */
        function IsHTML($bool)
        {
            if ($bool == true)
                $this->ContentType = "text/html";
            else
                $this->ContentType = "text/plain";
        } 

        /**
         * Sets Mailer to send message using SMTP.
         * Returns void.
         * 
         * @access public 
         * @return void 
         */
        function IsSMTP()
        {
            $this->Mailer = "smtp";
        } 

        /**
         * Sets Mailer to send message using PHP mail() function.
         * Returns void.
         * 
         * @access public 
         * @return void 
         */
        function IsMail()
        {
            $this->Mailer = "mail";
        } 

        /**
         * Sets Mailer to send message using the $Sendmail program.
         * Returns void.
         * 
         * @access public 
         * @return void 
         */
        function IsSendmail()
        {
            $this->Mailer = "sendmail";
        } 

        /**
         * Sets Mailer to send message using the qmail MTA.  Returns void.
         * 
         * @access public 
         * @return void 
         */
        function IsQmail()
        { 
            // $this->Sendmail = "/var/qmail/bin/qmail-inject";
            $this->Sendmail = "/var/qmail/bin/sendmail";
            $this->Mailer = "sendmail";
        } 
        // ///////////////////////////////////////////////
        // RECIPIENT METHODS
        // ///////////////////////////////////////////////
        /**
         * Adds a "To" address.  Returns void.
         * 
         * @access public 
         * @return void 
         */
        function AddAddress($address, $name = "")
        {
            $cur = count($this->to);
            $this->to[$cur][0] = trim($address);
            $this->to[$cur][1] = $name;
        } 

        /**
         * Adds a "Cc" address. Note: this function works
         * with the SMTP mailer on win32, not with the "mail"
         * mailer.  This is a PHP bug that has been submitted
         * on http://bugs.php.net. The *NIX version of PHP
         * functions correctly. Returns void.
         * 
         * @access public 
         * @return void 
         */
        function AddCC($address, $name = "")
        {
            $cur = count($this->cc);
            $this->cc[$cur][0] = trim($address);
            $this->cc[$cur][1] = $name;
        } 

        /**
         * Adds a "Bcc" address. Note: this function works
         * with the SMTP mailer on win32, not with the "mail"
         * mailer.  This is a PHP bug that has been submitted
         * on http://bugs.php.net. The *NIX version of PHP
         * functions correctly.
         * Returns void.
         * 
         * @access public 
         * @return void 
         */
        function AddBCC($address, $name = "")
        {
            $cur = count($this->bcc);
            $this->bcc[$cur][0] = trim($address);
            $this->bcc[$cur][1] = $name;
        } 

        /**
         * Adds a "Reply-to" address.  Returns void.
         * 
         * @access public 
         * @return void 
         */
        function AddReplyTo($address, $name = "")
        {
            $cur = count($this->ReplyTo);
            $this->ReplyTo[$cur][0] = trim($address);
            $this->ReplyTo[$cur][1] = $name;
        } 
        // ///////////////////////////////////////////////
        // MAIL SENDING METHODS
        // ///////////////////////////////////////////////
        /**
         * Creates message and assigns Mailer. If the message is
         * not sent successfully then it returns false.  Use the ErrorInfo
         * variable to view description of the error.  Returns bool.
         * 
         * @access public 
         * @return bool 
         */
        function Send()
        {
            $header = "";
            $body = "";

            if ((count($this->to) + count($this->cc) + count($this->bcc)) < 1)
            {
                $this->error_handler("You must provide at least one recipient email address");
                return false;
            } 
            // Set whether the message is multipart/alternative
            if (!empty($this->AltBody))
                $this->ContentType = "multipart/alternative"; 
            // Attach sender information & date
            $header = $this->received();
            $header .= sprintf("Date: %s%s", $this->rfc_date(), $this->LE);
            $header .= $this->create_header();

            if (!$body = $this->create_body())
                return false; 
            // echo "<pre>".$header . $body . "</pre>"; // debugging
            // Choose the mailer
            if ($this->Mailer == "sendmail")
            {
                if (!$this->sendmail_send($header, $body))
                    return false;
            } elseif ($this->Mailer == "mail")
            {
                if (!$this->mail_send($header, $body))
                    return false;
            } elseif ($this->Mailer == "smtp")
            {
                if (!$this->smtp_send($header, $body))
                    return false;
            } 
            else
            {
                $this->error_handler(sprintf("%s mailer is not supported", $this->Mailer));
                return false;
            } 

            return true;
        } 

        /**
         * Sends mail message to an assigned queue directory.  Has an optional 
         * sendTime argument.  This is used when the user wants the 
         * message to be sent from the queue at a predetermined time. 
         * The data must be a valid timestamp like that returned from 
         * the time() or strtotime() functions.  Returns false on failure 
         * or the message file name if success.
         * 
         * @access public 
         * @return string 
         */
        function SendToQueue($queue_path, $send_time = 0)
        {
            $message = array();
            $header = "";
            $body = ""; 
            // If invalid or empty just set to the current time
            if ($send_time == 0)
                $send_time = time();

            if (!is_dir($queue_path))
            {
                $this->error_handler("The supplied queue directory does not exist");
                return false;
            } 

            if ((count($this->to) + count($this->cc) + count($this->bcc)) < 1)
            {
                $this->error_handler("You must provide at least one recipient email address");
                return false;
            } 
            // Set whether the message is multipart/alternative
            if (!empty($this->AltBody))
                $this->ContentType = "multipart/alternative";

            $header = $this->create_header();
            if (!$body = $this->create_body())
                return false; 
            // Seed randomizer
            mt_srand(time());
            $msg_id = md5(uniqid(mt_rand()));

            $fp = @fopen($queue_path . $msg_id . ".pqm", "wb");
            if (!$fp)
            {
                $this->error_handler(sprintf("Could not write to %s directory", $queue_path));
                return false;
            } 

            $message[] = sprintf("----START PQM HEADER----%s", $this->LE);
            $message[] = sprintf("SendTime: %s%s", $send_time, $this->LE);
            $message[] = sprintf("Mailer: %s%s", $this->Mailer, $this->LE); 
            // Choose the mailer
            if ($this->Mailer == "sendmail")
            {
                $message[] = sprintf("Sendmail: %s%s", $this->Sendmail, $this->LE);
                $message[] = sprintf("Sender: %s%s", $this->Sender, $this->LE);
            } elseif ($this->Mailer == "mail")
            {
                $message[] = sprintf("Sender: %s%s", $this->Sender, $this->LE);
                $message[] = sprintf("Subject: %s%s", $this->Subject, $this->LE);
                $message[] = sprintf("to: %s%s", $this->addr_list($this->to), $this->LE);
            } elseif ($this->Mailer == "smtp")
            {
                $message[] = sprintf("Host: %s%s", $this->Host, $this->LE);
                $message[] = sprintf("Port: %d%s", $this->Port, $this->LE);
                $message[] = sprintf("Helo: %s%s", $this->Helo, $this->LE);
                $message[] = sprintf("Timeout: %d%s", $this->Timeout, $this->LE);

                if ($this->SMTPAuth)
                    $auth_no = 1;
                else
                    $auth_no = 0;
                $message[] = sprintf("SMTPAuth: %d%s", $auth_no, $this->LE);
                $message[] = sprintf("Username: %s%s", $this->Username, $this->LE);
                $message[] = sprintf("Password: %s%s", $this->Password, $this->LE);
                $message[] = sprintf("From: %s%s", $this->From, $this->LE);

                $message[] = sprintf("to: %s%s", $this->addr_list($this->to), $this->LE);
                $message[] = sprintf("cc: %s%s", $this->addr_list($this->cc), $this->LE);
                $message[] = sprintf("bcc: %s%s", $this->addr_list($this->bcc), $this->LE);
            } 
            else
            {
                $this->error_handler(sprintf("%s mailer is not supported", $this->Mailer));
                return false;
            } 

            $message[] = sprintf("----END PQM HEADER----%s", $this->LE); // end of pqm header        
            $message[] = $header;
            $message[] = $body;

            if (fwrite($fp, join("", $message)) == -1)
            {
                $this->error_handler("Write to file failed");
                return false;
            } 
            fclose($fp);

            return ($msg_id . ".pqm");
        } 

        /**
         * Sends mail using the $Sendmail program.  Returns bool.
         * 
         * @access private 
         * @return bool 
         */
        function sendmail_send($header, $body)
        {
            if ($this->Sender != "")
                $sendmail = sprintf("%s -oi -f %s -t", $this->Sendmail, $this->Sender);
            else
                $sendmail = sprintf("%s -oi -t", $this->Sendmail);

            if (!@$mail = popen($sendmail, "w"))
            {
                $this->error_handler(sprintf("Could not execute %s", $this->Sendmail));
                return false;
            } 

            fputs($mail, $header);
            fputs($mail, $body);

            $result = pclose($mail) >> 8 &0xFF;
            if ($result != 0)
            {
                $this->error_handler(sprintf("Could not execute %s", $this->Sendmail));
                return false;
            } 

            return true;
        } 

        /**
         * Sends mail using the PHP mail() function.  Returns bool.
         * 
         * @access private 
         * @return bool 
         */
        function mail_send($header, $body)
        { 
           //Dont change below
		   //end 
		    // $to = substr($this->addr_append("To", $this->to), 4, -2);
            // Cannot add Bcc's to the $to
            $to = $this->to[0][0]; // no extra comma
            for($i = 1; $i < count($this->to); $i++)
            $to .= sprintf(",%s", $this->to[$i][0]);

            if ($this->Sender != "" && PHP_VERSION >= "4.0")
            {
                $old_from = ini_get("sendmail_from");
                ini_set("sendmail_from", $this->Sender);
            } 

            if ($this->Sender != "" && PHP_VERSION >= "4.0.5")
            { 
                // The fifth parameter to mail is only available in PHP >= 4.0.5
                $params = sprintf("-oi -f %s", $this->Sender);
                $rt = mail($to, $this->encode_header($this->Subject), $body, $header, $params);
            } 
            else
            {
                $rt = mail($to, $this->encode_header($this->Subject), $body, $header);
            } 

            if (isset($old_from))
                ini_set("sendmail_from", $old_from);
//if you face some problem with getting email, remove comment from following line
/*
            if (!$rt)
            {
                $this->error_handler("Could not instantiate mail()");
                return false;
            } 
*/
            return true;
        } 

        /**
         * Sends mail via SMTP using PhpSMTP (Author:
         * Chris Ryan).  Returns bool.  Returns false if there is a
         * bad MAIL FROM, RCPT, or DATA input.
         * 
         * @access private 
         * @return bool 
         */
        function smtp_send($header, $body)
        { 
            // Include SMTP class code, but not twice
            include_once($this->PluginDir . "class.smtp.php");

            $smtp = new SMTP;

            $smtp->do_debug = $this->SMTPDebug; 
            // Try to connect to all SMTP servers
            $hosts = explode(";", $this->Host);
            $index = 0;
            $connection = false;
            $smtp_from = "";
            $bad_rcpt = array();
            $e = ""; 
            // Retry while there is no connection
            while ($index < count($hosts) && $connection == false)
            {
                if (strstr($hosts[$index], ":"))
                    list($host, $port) = explode(":", $hosts[$index]);
                else
                {
                    $host = $hosts[$index];
                    $port = $this->Port;
                } 

                if ($smtp->Connect($host, $port, $this->Timeout))
                    $connection = true; 
                // printf("%s host could not connect<br>", $hosts[$index]); //debug only
                $index++;
            } 
            if (!$connection)
            {
                $this->error_handler("SMTP Error: could not connect to SMTP host server(s)");
                return false;
            } 
            // Must perform HELO before authentication
            if ($this->Helo != '')
                $smtp->Hello($this->Helo);
            else
                $smtp->Hello($this->get_server_hostname()); 
            // If user requests SMTP authentication
            if ($this->SMTPAuth)
            {
                if (!$smtp->Authenticate($this->Username, $this->Password))
                {
                    $this->error_handler("SMTP Error: Could not authenticate");
                    return false;
                } 
            } 

            if ($this->Sender == "")
                $smtp_from = $this->From;
            else
                $smtp_from = $this->Sender;

            if (!$smtp->Mail(sprintf("<%s>", $smtp_from)))
            {
                $e = sprintf("SMTP Error: From address [%s] failed", $smtp_from);
                $this->error_handler($e);
                return false;
            } 
            // Attempt to send attach all recipients
            for($i = 0; $i < count($this->to); $i++)
            {
                if (!$smtp->Recipient(sprintf("<%s>", $this->to[$i][0])))
                    $bad_rcpt[] = $this->to[$i][0];
            } 
            for($i = 0; $i < count($this->cc); $i++)
            {
                if (!$smtp->Recipient(sprintf("<%s>", $this->cc[$i][0])))
                    $bad_rcpt[] = $this->cc[$i][0];
            } 
            for($i = 0; $i < count($this->bcc); $i++)
            {
                if (!$smtp->Recipient(sprintf("<%s>", $this->bcc[$i][0])))
                    $bad_rcpt[] = $this->bcc[$i][0];
            } 
            // Create error message
            if (count($bad_rcpt) > 0)
            {
                for($i = 0; $i < count($bad_rcpt); $i++)
                {
                    if ($i != 0)
                        $e .= ", ";
                    $e .= $bad_rcpt[$i];
                } 
                $e = sprintf("SMTP Error: The following recipients failed [%s]", $e);
                $this->error_handler($e);

                return false;
            } 

            if (!$smtp->Data(sprintf("%s%s", $header, $body)))
            {
                $this->error_handler("SMTP Error: Data not accepted");
                return false;
            } 
            $smtp->Quit();
            $smtp->Close();

            return true;
        } 
        // ///////////////////////////////////////////////
        // MESSAGE CREATION METHODS
        // ///////////////////////////////////////////////
        /**
         * Creates recipient headers.  Returns string.
         * 
         * @access private 
         * @return string 
         */
        function addr_append($type, $addr)
        {
            $addr_str = $type . ": ";
            $addr_str .= $this->addr_format($addr[0]);
            if (count($addr) > 1)
            {
                for($i = 1; $i < count($addr); $i++)
                {
                    $addr_str .= sprintf(", %s", $this->addr_format($addr[$i]));
                } 
                $addr_str .= $this->LE;
            } 
            else
                $addr_str .= $this->LE;

            return($addr_str);
        } 

        /**
         * Creates a semicolon delimited list for use in pqm files.
         * 
         * @access private 
         * @return string 
         */
        function addr_list($list_array)
        {
            $addr_list = "";
            for($i = 0; $i < count($list_array); $i++)
            {
                if ($i > 0)
                    $addr_list .= ";";
                $addr_list .= $list_array[$i][0];
            } 

            return $addr_list;
        } 

        /**
         * Formats an address correctly.
         * 
         * @access private 
         * @return string 
         */
        function addr_format($addr)
        {
            if (empty($addr[1]))
                $formatted = $addr[0];
            else
                $formatted = sprintf('%s <%s>', $this->encode_header($addr[1], 'phrase'), $addr[0]);

            return $formatted;
        } 

        /**
         * Wraps message for use with mailers that do not
         * automatically perform wrapping and for quoted-printable.
         * Original written by philippe.  Returns string.
         * 
         * @access private 
         * @return string 
         */
        function word_wrap($message, $length, $qp_mode = false)
        {
            if ($qp_mode)
                $soft_break = sprintf(" =%s", $this->LE);
            else
                $soft_break = $this->LE;

            $message = $this->fix_eol($message);
            if (substr($message, -1) == $this->LE)
                $message = substr($message, 0, -1);

            $line = explode($this->LE, $message);
            $message = "";
            for ($i = 0 ;$i < count($line); $i++)
            {
                $line_part = explode(" ", $line[$i]);
                $buf = "";
                for ($e = 0; $e < count($line_part); $e++)
                {
                    $word = $line_part[$e];
                    if ($qp_mode and (strlen($word) > $length))
                    {
                        $space_left = $length - strlen($buf) - 1;
                        if ($e != 0)
                        {
                            if ($space_left > 20)
                            {
                                $len = $space_left;
                                if (substr($word, $len - 1, 1) == "=")
                                    $len--;
                                elseif (substr($word, $len - 2, 1) == "=")
                                    $len -= 2;
                                $part = substr($word, 0, $len);
                                $word = substr($word, $len);
                                $buf .= " " . $part;
                                $message .= $buf . sprintf("=%s", $this->LE);
                            } 
                            else
                            {
                                $message .= $buf . $soft_break;
                            } 
                            $buf = "";
                        } 
                        while (strlen($word) > 0)
                        {
                            $len = $length;
                            if (substr($word, $len - 1, 1) == "=")
                                $len--;
                            elseif (substr($word, $len - 2, 1) == "=")
                                $len -= 2;
                            $part = substr($word, 0, $len);
                            $word = substr($word, $len);

                            if (strlen($word) > 0)
                                $message .= $part . sprintf("=%s", $this->LE);
                            else
                                $buf = $part;
                        } 
                    } 
                    else
                    {
                        $buf_o = $buf;
                        if ($e == 0)
                            $buf .= $word;
                        else
                            $buf .= " " . $word;
                        if (strlen($buf) > $length and $buf_o != "")
                        {
                            $message .= $buf_o . $soft_break;
                            $buf = $word;
                        } 
                    } 
                } 
                $message .= $buf . $this->LE;
            } 

            return ($message);
        } 

        /**
         * Set the body wrapping.
         * 
         * @access private 
         * @return void 
         */
        function SetWordWrap()
        {
            if ($this->WordWrap < 1)
                return;

            switch ($this->message_type)
            {
                case "alt":
                case "alt_attachment":
                    $this->AltBody = $this->word_wrap($this->AltBody, $this->WordWrap);
                    break;
                default:
                    $this->Body = $this->word_wrap($this->Body, $this->WordWrap);
                    break;
            } 
        } 

        /**
         * Assembles message header.  Returns a string if successful
         * or false if unsuccessful.
         * 
         * @access private 
         * @return string 
         */
        function create_header()
        {
            $header = array(); 
            // Set the boundaries
            $uniq_id = md5(uniqid(time()));
            $this->boundary[1] = "b1_" . $uniq_id;
            $this->boundary[2] = "b2_" . $uniq_id; 
            // To be created automatically by mail()
            if ($this->Mailer != "mail")
            {
                if (count($this->to) > 0)
                    $header[] = $this->addr_append("To", $this->to);
                else if (count($this->cc) == 0)
                    $header[] = "To: undisclosed-recipients:;" . $this->LE;
            } 

            $from = array();
            $from[0][0] = trim($this->From);
            $from[0][1] = $this->FromName;
            $header[] = $this->addr_append("From", $from);

            if (count($this->cc) > 0)
                $header[] = $this->addr_append("Cc", $this->cc); 
            // sendmail and mail() extract Bcc from the header before sending
            if ((($this->Mailer == "sendmail") || ($this->Mailer == "mail")) && (count($this->bcc) > 0))
                $header[] = $this->addr_append("Bcc", $this->bcc);

            if (count($this->ReplyTo) > 0)
                $header[] = $this->addr_append("Reply-to", $this->ReplyTo); 
            // mail() sets the subject itself
            if ($this->Mailer != "mail")
                $header[] = sprintf("Subject: %s%s", $this->encode_header(trim($this->Subject)), $this->LE);

            $header[] = sprintf("Message-ID: <%s@%s>%s", $uniq_id, $this->get_server_hostname(), $this->LE);
            $header[] = sprintf("X-Priority: %d%s", $this->Priority, $this->LE);
            $header[] = sprintf("X-Mailer: phpmailer [version %s]%s", $this->Version, $this->LE);
            if ($this->Sender == "")
                $header[] = sprintf("Return-Path: %s%s", trim($this->From), $this->LE);
            else
                $header[] = sprintf("Return-Path: %s%s", trim($this->Sender), $this->LE);

            if ($this->ConfirmReadingTo != "")
                $header[] = sprintf("Disposition-Notification-To: <%s>%s",
                    trim($this->ConfirmReadingTo), $this->LE); 
            // Add custom headers
            for($index = 0; $index < count($this->CustomHeader); $index++)
            $header[] = sprintf("%s: %s%s", trim($this->CustomHeader[$index][0]), $this->encode_header(trim($this->CustomHeader[$index][1])), $this->LE);

            if ($this->UseMSMailHeaders)
                $header[] = $this->AddMSMailHeaders();

            $header[] = sprintf("MIME-Version: 1.0%s", $this->LE); 
            // Determine what type of message this is
            if (count($this->attachment) < 1 && strlen($this->AltBody) < 1)
                $this->message_type = "plain";
            else
            {
                if (count($this->attachment) > 0)
                    $this->message_type = "attachments";
                if (strlen($this->AltBody) > 0 && count($this->attachment) < 1)
                    $this->message_type = "alt";
                if (strlen($this->AltBody) > 0 && count($this->attachment) > 0)
                    $this->message_type = "alt_attachments";
            } 

            switch ($this->message_type)
            {
                case "plain":
                    $header[] = sprintf("Content-Transfer-Encoding: %s%s",
                        $this->Encoding, $this->LE);
                    $header[] = sprintf("Content-Type: %s; charset=\"%s\"",
                        $this->ContentType, $this->CharSet);
                    break;
                case "attachments":
                case "alt_attachments":
                    if ($this->EmbeddedImageCount() > 0)
                    {
                        $header[] = sprintf("Content-Type: %s;%s\ttype=\"text/html\";%s\tboundary=\"%s\"%s",
                            "multipart/related", $this->LE, $this->LE,
                            $this->boundary[1], $this->LE);
                    } 
                    else
                    {
                        $header[] = sprintf("Content-Type: %s;%s",
                            "multipart/mixed", $this->LE);
                        $header[] = sprintf("\tboundary=\"%s\"%s", $this->boundary[1], $this->LE);
                    } 
                    break;
                case "alt":
                    $header[] = sprintf("Content-Type: %s;%s",
                        "multipart/alternative", $this->LE);
                    $header[] = sprintf("\tboundary=\"%s\"%s", $this->boundary[1], $this->LE);
                    break;
            } 
            // No additional lines when using mail() function
            if ($this->Mailer != "mail")
                $header[] = $this->LE . $this->LE;

            return(join("", $header));
        } 

        /**
         * Assembles the message body.  Returns a string if successful
         * or false if unsuccessful.
         * 
         * @access private 
         * @return string 
         */
        function create_body()
        {
            $body = array();

            $this->SetWordWrap();

            switch ($this->message_type)
            {
                case "alt": 
                    // Return text of body
                    $bndry = new Boundary($this->boundary[1]);
                    $bndry->CharSet = $this->CharSet;
                    $bndry->Encoding = $this->Encoding;
                    $body[] = $bndry->GetSource();

                    $body[] = $this->encode_string($this->AltBody, $this->Encoding);
                    $body[] = $this->LE . $this->LE;

                    $bndry = new Boundary($this->boundary[1]);
                    $bndry->CharSet = $this->CharSet;
                    $bndry->ContentType = "text/html";
                    $bndry->Encoding = $this->Encoding;
                    $body[] = $bndry->GetSource();

                    $body[] = $this->encode_string($this->Body, $this->Encoding);
                    $body[] = $this->LE . $this->LE; 
                    // End the boundary
                    $body[] = sprintf("%s--%s--%s", $this->LE,
                        $this->boundary[1], $this->LE . $this->LE);
                    break;
                case "plain":
                    $body[] = $this->encode_string($this->Body, $this->Encoding);
                    break;
                case "attachments":
                    $bndry = new Boundary($this->boundary[1]);
                    $bndry->CharSet = $this->CharSet;
                    $bndry->ContentType = $this->ContentType;
                    $bndry->Encoding = $this->Encoding;
                    $body[] = $bndry->GetSource(false) . $this->LE;
                    $body[] = $this->encode_string($this->Body, $this->Encoding);
                    $body[] = $this->LE;

                    if (!$body[] = $this->attach_all())
                        return false;
                    break;
                case "alt_attachments":
                    $body[] = sprintf("--%s%s", $this->boundary[1], $this->LE);
                    $body[] = sprintf("Content-Type: %s;%s" . "\tboundary=\"%s\"%s",
                        "multipart/alternative", $this->LE,
                        $this->boundary[2], $this->LE . $this->LE); 
                    // Create text body
                    $bndry = new Boundary($this->boundary[2]);
                    $bndry->CharSet = $this->CharSet;
                    $bndry->ContentType = "text/plain";
                    $bndry->Encoding = $this->Encoding;
                    $body[] = $bndry->GetSource() . $this->LE;

                    $body[] = $this->encode_string($this->AltBody, $this->Encoding);
                    $body[] = $this->LE . $this->LE; 
                    // Create the HTML body
                    $bndry = new Boundary($this->boundary[2]);
                    $bndry->CharSet = $this->CharSet;
                    $bndry->ContentType = "text/html";
                    $bndry->Encoding = $this->Encoding;
                    $body[] = $bndry->GetSource() . $this->LE;

                    $body[] = $this->encode_string($this->Body, $this->Encoding);
                    $body[] = $this->LE . $this->LE;

                    $body[] = sprintf("%s--%s--%s", $this->LE,
                        $this->boundary[2], $this->LE . $this->LE);

                    if (!$body[] = $this->attach_all())
                        return false;
                    break;
            } 
            $sBody = join("", $body);

            return $sBody;
        } 
        // ///////////////////////////////////////////////
        // ATTACHMENT METHODS
        // ///////////////////////////////////////////////
        /**
         * Adds an attachment from a path on the filesystem.
         * Checks if attachment is valid and then adds
         * the attachment to the list.
         * Returns false if the file could not be found
         * or accessed.
         * 
         * @access public 
         * @return bool 
         */
        function AddAttachment($path, $name = "", $encoding = "base64", $type = "application/octet-stream")
        {
            if (!@is_file($path))
            {
                $this->error_handler(sprintf("Could not access [%s] file", $path));
                return false;
            } 

            $filename = basename($path);
            if ($name == "")
                $name = $filename; 
            // Append to $attachment array
            $cur = count($this->attachment);
            $this->attachment[$cur][0] = $path;
            $this->attachment[$cur][1] = $filename;
            $this->attachment[$cur][2] = $name;
            $this->attachment[$cur][3] = $encoding;
            $this->attachment[$cur][4] = $type;
            $this->attachment[$cur][5] = false; // isStringAttachment
            $this->attachment[$cur][6] = "attachment";
            $this->attachment[$cur][7] = 0;

            return true;
        } 

        /**
         * Attaches all fs, string, and binary attachments to the message.
         * Returns a string if successful or false if unsuccessful.
         * 
         * @access private 
         * @return string 
         */
        function attach_all()
        { 
            // Return text of body
            $mime = array(); 
            // Add all attachments
            for($i = 0; $i < count($this->attachment); $i++)
            { 
                // Check for string attachment
                $bString = $this->attachment[$i][5];
                if ($bString)
                {
                    $string = $this->attachment[$i][0];
                } 
                else
                {
                    $path = $this->attachment[$i][0];
                } 
                $filename = $this->attachment[$i][1];
                $name = $this->attachment[$i][2];
                $encoding = $this->attachment[$i][3];
                $type = $this->attachment[$i][4];
                $disposition = $this->attachment[$i][6];
                $cid = $this->attachment[$i][7];

                $mime[] = sprintf("--%s%s", $this->boundary[1], $this->LE);
                $mime[] = sprintf("Content-Type: %s; name=\"%s\"%s", $type, $name, $this->LE);
                $mime[] = sprintf("Content-Transfer-Encoding: %s%s", $encoding, $this->LE);

                if ($disposition == "inline")
                    $mime[] = sprintf("Content-ID: <%s>%s", $cid, $this->LE);

                $mime[] = sprintf("Content-Disposition: %s; filename=\"%s\"%s",
                    $disposition, $name, $this->LE . $this->LE); 
                // Encode as string attachment
                if ($bString)
                {
                    if (!$mime[] = $this->encode_string($string, $encoding))
                        return false;
                    $mime[] = $this->LE . $this->LE;
                } 
                else
                {
                    if (!$mime[] = $this->encode_file($path, $encoding))
                        return false;
                    $mime[] = $this->LE . $this->LE;
                } 
            } 

            $mime[] = sprintf("--%s--%s", $this->boundary[1], $this->LE);

            return(join("", $mime));
        } 

        /**
         * Encodes attachment in requested format.  Returns a
         * string if successful or false if unsuccessful.
         * 
         * @access private 
         * @return string 
         */
        function encode_file ($path, $encoding = "base64")
        {
            if (!@$fd = fopen($path, "rb"))
            {
                $this->error_handler(sprintf("File Error: Could not open file %s", $path));
                return false;
            } 
            $file_buffer = fread($fd, filesize($path));
            $file_buffer = $this->encode_string($file_buffer, $encoding);
            fclose($fd);

            return $file_buffer;
        } 

        /**
         * Encodes string to requested format. Returns a
         * string if successful or false if unsuccessful.
         * 
         * @access private 
         * @return string 
         */
        function encode_string ($str, $encoding = "base64")
        {
            switch (strtolower($encoding))
            {
                case "base64": 
                    // chunk_split is found in PHP >= 3.0.6
                    $encoded = chunk_split(base64_encode($str));
                    break;

                case "7bit":
                case "8bit":
                    $encoded = $this->fix_eol($str);
                    if (substr($encoded, -2) != $this->LE)
                        $encoded .= $this->LE;
                    break;

                case "binary":
                    $encoded = $str;
                    break;

                case "quoted-printable":
                    $encoded = $this->encode_qp($str);
                    break;

                default:
                    $this->error_handler(sprintf("Unknown encoding: %s", $encoding));
                    return false;
            } 
            return($encoded);
        } 

        /**
         * Encode a header string to best of Q, B, quoted or none.  Returns a string.
         * 
         * @access private 
         * @return string 
         */
        function encode_header ($str, $position = 'text')
        {
            $x = 0;

            switch (strtolower($position))
            {
                case 'phrase':
                    if (preg_match_all('/[\200-\377]/', $str, $matches) == 0)
                    { 
                        // Can't use addslashes as we don't know what value has magic_quotes_sybase.
                        $encoded = addcslashes($str, '\000-\037\177');
                        $encoded = preg_replace('/([\"])/', '\\"', $encoded);

                        if (($str == $encoded) && (preg_match_all('/[^A-Za-z0-9!#$%&\'*+\/=?^_`{|}~ -]/', $str, $matches) == 0))
                            return ($encoded);
                        else
                            return ("\"$encoded\"");
                    } 
                    $x = preg_match_all('/[^\040\041\043-\133\135-\176]/', $str, $matches);
                    break;
                case 'comment':
                    $x = preg_match_all('/[()"]/', $str, $matches); 
                    // Fall-through
                case 'text':
                default:
                    $x += preg_match_all('/[\000-\010\013\014\016-\037\177-\377]/', $str, $matches);
                    break;
            } 

            if ($x == 0)
                return ($str);

            $maxlen = 75 - 7 - strlen($this->CharSet); 
            // Try to select the encoding which should produce the shortest output
            if (strlen($str) / 3 < $x)
            {
                $encoding = 'B';
                $encoded = base64_encode($str);
                $maxlen -= $maxlen % 4;
                $encoded = trim(chunk_split($encoded, $maxlen, "\n"));
            } 
            else
            {
                $encoding = 'Q';
                $encoded = $this->encode_q($str, $position);
                $encoded = $this->word_wrap($encoded, $maxlen, true);
                $encoded = str_replace("=" . $this->LE, "\n", trim($encoded));
            } 

            $encoded = preg_replace('/^(.*)$/m', " =?" . $this->CharSet . "?$encoding?\\1?=", $encoded);
            $encoded = trim(str_replace("\n", $this->LE, $encoded));

            return($encoded);
        } 

        /**
         * Encode string to quoted-printable.  Returns a string.
         * 
         * @access private 
         * @return string 
         */
        function encode_qp ($str)
        {
            $encoded = $this->fix_eol($str);
            if (substr($encoded, -2) != $this->LE)
                $encoded .= $this->LE; 
            // Replace every high ascii, control and = characters
            $encoded = preg_replace('/([\000-\010\013\014\016-\037\075\177-\377])/e',
                "'='.sprintf('%02X', ord('\\1'))", $encoded); 
            // Replace every spaces and tabs when it's the last character on a line
            $encoded = preg_replace("/([\011\040])" . $this->LE . "/e",
                "'='.sprintf('%02X', ord('\\1')).'" . $this->LE . "'", $encoded); 
            // Maximum line length of 76 characters before CRLF (74 + space + '=')
            $encoded = $this->word_wrap($encoded, 74, true);

            return $encoded;
        } 

        /**
         * Encode string to q encoding.  Returns a string.
         * 
         * @access private 
         * @return string 
         */
        function encode_q ($str, $position = 'text')
        { 
            // There should not be any EOL in the string
            $encoded = preg_replace("[\r\n]", "", $str);

            switch (strtolower($position))
            {
                case 'phrase':
                    $encoded = preg_replace("/([^A-Za-z0-9!*+\/ -])/e", "'='.sprintf('%02X', ord('\\1'))", $encoded);
                    break;
                case 'comment':
                    $encoded = preg_replace("/([\(\)\"])/e", "'='.sprintf('%02X', ord('\\1'))", $encoded); 
                    // Fall-through
                case 'text':
                default: 
                    // Replace every high ascii, control =, ? and _ characters
                    $encoded = preg_replace('/([\000-\011\013\014\016-\037\075\077\137\177-\377])/e',
                        "'='.sprintf('%02X', ord('\\1'))", $encoded);
                    break;
            } 
            // Replace every spaces to _ (more readable than =20)
            $encoded = str_replace(" ", "_", $encoded);

            return $encoded;
        } 

        /**
         * Adds a string or binary attachment (non-filesystem) to the list.
         * This method can be used to attach ascii or binary data,
         * such as a BLOB record from a database.
         * 
         * @access public 
         * @return void 
         */
        function AddStringAttachment($string, $filename, $encoding = "base64", $type = "application/octet-stream")
        { 
            // Append to $attachment array
            $cur = count($this->attachment);
            $this->attachment[$cur][0] = $string;
            $this->attachment[$cur][1] = $filename;
            $this->attachment[$cur][2] = $filename;
            $this->attachment[$cur][3] = $encoding;
            $this->attachment[$cur][4] = $type;
            $this->attachment[$cur][5] = true; // isString
            $this->attachment[$cur][6] = "attachment";
            $this->attachment[$cur][7] = 0;
        } 

        /**
         * Adds an embedded attachment.  This can include images, sounds, and 
         * just about any other document.
         * 
         * @param cid $ this is the Content Id of the attachment.  Use this to identify
         *           the Id for accessing the image in an HTML form.
         * @access public 
         * @return bool 
         */
        function AddEmbeddedImage($path, $cid, $name = "", $encoding = "base64", $type = "application/octet-stream")
        {
            if (!@is_file($path))
            {
                $this->error_handler(sprintf("Could not access [%s] file", $path));
                return false;
            } 

            $filename = basename($path);
            if ($name == "")
                $name = $filename; 
            // Append to $attachment array
            $cur = count($this->attachment);
            $this->attachment[$cur][0] = $path;
            $this->attachment[$cur][1] = $filename;
            $this->attachment[$cur][2] = $name;
            $this->attachment[$cur][3] = $encoding;
            $this->attachment[$cur][4] = $type;
            $this->attachment[$cur][5] = false; // isStringAttachment
            $this->attachment[$cur][6] = "inline";
            $this->attachment[$cur][7] = $cid;

            return true;
        } 

        /**
         * Returns the number of embedded images in an email.
         * 
         * @access private 
         * @return int 
         */
        function EmbeddedImageCount()
        {
            $ret = 0;
            for($i = 0; $i < count($this->attachment); $i++)
            {
                if ($this->attachment[$i][6] == "inline")
                    $ret++;
            } 

            return $ret;
        } 
        // ///////////////////////////////////////////////
        // MESSAGE RESET METHODS
        // ///////////////////////////////////////////////
        /**
         * Clears all recipients assigned in the TO array.  Returns void.
         * 
         * @access public 
         * @return void 
         */
        function ClearAddresses()
        {
            $this->to = array();
        } 

        /**
         * Clears all recipients assigned in the CC array.  Returns void.
         * 
         * @access public 
         * @return void 
         */
        function ClearCCs()
        {
            $this->cc = array();
        } 

        /**
         * Clears all recipients assigned in the BCC array.  Returns void.
         * 
         * @access public 
         * @return void 
         */
        function ClearBCCs()
        {
            $this->bcc = array();
        } 

        /**
         * Clears all recipients assigned in the ReplyTo array.  Returns void.
         * 
         * @access public 
         * @return void 
         */
        function ClearReplyTos()
        {
            $this->ReplyTo = array();
        } 

        /**
         * Clears all recipients assigned in the TO, CC and BCC
         * array.  Returns void.
         * 
         * @access public 
         * @return void 
         */
        function ClearAllRecipients()
        {
            $this->to = array();
            $this->cc = array();
            $this->bcc = array();
        } 

        /**
         * Clears all previously set filesystem, string, and binary
         * attachments.  Returns void.
         * 
         * @access public 
         * @return void 
         */
        function ClearAttachments()
        {
            $this->attachment = array();
        } 

        /**
         * Clears all custom headers.  Returns void.
         * 
         * @access public 
         * @return void 
         */
        function ClearCustomHeaders()
        {
            $this->CustomHeader = array();
        } 
        // ///////////////////////////////////////////////
        // MISCELLANEOUS METHODS
        // ///////////////////////////////////////////////
        /**
         * Adds the error message to the error container.
         * Returns void.
         * 
         * @access private 
         * @return void 
         */
        function error_handler($msg)
        {
            echo $msg;
            exit;
            $this->ErrorInfo = $msg;
        } 

        /**
         * Returns the proper RFC 822 formatted date. Returns string.
         * 
         * @access private 
         * @return string 
         */
        function rfc_date()
        {
            $tz = date("Z");
            $tzs = ($tz < 0) ? "-" : "+";
            $tz = abs($tz);
            $tz = ($tz / 3600) * 100 + ($tz % 3600) / 60;
            $date = sprintf("%s %s%04d", date("D, j M Y H:i:s"), $tzs, $tz);
            return $date;
        } 

        /**
         * Returns received header for message tracing. Returns string.
         * 
         * @access private 
         * @return string 
         */
        function received()
        { 
            // Check for vars because they might not exist.  Possibly
            // write a small retrieval function (that mailer can use too!)
            if ($this->get_server_var('SERVER_NAME') != '')
            {
                $protocol = ($this->get_server_var('HTTPS') == 'on') ? 'HTTPS' : 'HTTP';
                $remote = $this->get_server_var('REMOTE_HOST');
                if ($remote == '')
                    $remote = 'phpmailer';
                $remote .= ' ([' . $this->get_server_var('REMOTE_ADDR') . '])';
            } 
            else
            {
                $protocol = 'local';
                $remote = $this->get_server_var('USER');
                if ($remote == '')
                    $remote = 'phpmailer';
            } 

            $str = sprintf("Received: from %s %s\tby %s " . "with %s (phpmailer);%s\t%s%s",
                $remote,
                $this->LE,
                $this->get_server_hostname(),
                $protocol,
                $this->LE,
                $this->rfc_date(),
                $this->LE);

            return $str;
        } 

        /**
         * Returns the appropriate server variable.  Should work with both 
         * PHP 4.1.0+ as well as older versions.  Returns an empty string 
         * if nothing is found.
         * 
         * @access private 
         * @return mixed 
         */
        function get_server_var($varName)
        {
            global $HTTP_SERVER_VARS;
            global $HTTP_ENV_VARS;

            if (!isset($_SERVER))
            {
                $_SERVER = $HTTP_SERVER_VARS;
                if (!isset($_SERVER["REMOTE_ADDR"]))
                    $_SERVER = $HTTP_ENV_VARS; // must be Apache
            } 

            if (isset($_SERVER[$varName]))
                return $_SERVER[$varName];
            else
                return "";
        } 

        /**
         * Returns the server hostname or 'localhost.localdomain' if unknown.
         * 
         * @access private 
         * @return string 
         */
        function get_server_hostname()
        {
            if ($this->Hostname != '')
                return $this->Hostname;
            elseif ($this->get_server_var('SERVER_NAME') != '')
                return $this->get_server_var('SERVER_NAME');
            else
                return 'localhost.localdomain';
        } 

        /**
         * Changes every end of line from CR or LF to CRLF.  Returns string.
         * 
         * @access private 
         * @return string 
         */
        function fix_eol($str)
        {
            $str = str_replace("\r\n", "\n", $str);
            $str = str_replace("\r", "\n", $str);
            $str = str_replace("\n", $this->LE, $str);
            return $str;
        } 

        /**
         * Adds a custom header.  Returns void.
         * 
         * @access public 
         * @return void 
         */
        function AddCustomHeader($custom_header)
        { 
            // Append to $custom_header array
            $this->CustomHeader[] = explode(":", $custom_header, 2);
        } 

        /**
         * Adds all the Microsoft message headers.  Returns string.
         * 
         * @access private 
         * @return string 
         */
        function AddMSMailHeaders()
        {
            $MSHeader = "";
            if ($this->Priority == 1)
                $MSPriority = "High";
            elseif ($this->Priority == 5)
                $MSPriority = "Low";
            else
                $MSPriority = "Medium";

            $MSHeader .= sprintf("X-MSMail-Priority: %s%s", $MSPriority, $this->LE);
            $MSHeader .= sprintf("Importance: %s%s", $MSPriority, $this->LE);

            return($MSHeader);
        } 
    } 

    /**
     * Boundary - MIME message boundary class
     * 
     * @author Brent R. Matzelle 
     */
    class Boundary
    {
        /**
         * Sets the boundary ID.
         * 
         * @access private 
         * @var string 
         */
        var $ID = 0;

        /**
         * Sets the boundary Content Type.
         * 
         * @access public 
         * @var string 
         */
        var $ContentType = "text/plain";

        /**
         * Sets the Encoding.
         * 
         * @access public 
         * @var string 
         */
        var $Encoding = "";

        /**
         * Sets an attachment disposition.
         * 
         * @access public 
         * @var string 
         */
        var $Disposition = "";

        /**
         * Sets an attachment file name.
         * 
         * @access public 
         * @var string 
         */
        var $FileName = "";

        /**
         * Sets the Char set.
         * 
         * @access public 
         * @var string 
         */
        var $CharSet = "";

        /**
         * Sets the line endings of the message.  Default is "\n";
         * 
         * @access public 
         * @var string 
         */
        var $LE = "\n";

        /**
         * Main constructor.
         */
        function Boundary($boundary_id)
        {
            $this->ID = $boundary_id;
        } 

        /**
         * Returns the source of the boundary.
         * 
         * @access public 
         * @return string 
         */
        function GetSource($bLineEnding = true)
        {
            $mime = array();
            $mime[] = sprintf("--%s%s", $this->ID, $this->LE);
            $mime[] = sprintf("Content-Type: %s; charset = \"%s\"%s",
                $this->ContentType, $this->CharSet, $this->LE);
            $mime[] = sprintf("Content-Transfer-Encoding: %s%s", $this->Encoding,
                $this->LE);

            if (strlen($this->Disposition) > 0)
            {
                $mime[] = sprintf("Content-Disposition: %s;");
                if (strlen($this->FileName) > 0)
                    $mime[] = sprinf("filename=\"%s\"", $this->FileName);
            } 

            if ($bLineEnding)
                $mime[] = $this->LE;

            return join("", $mime);
        } 
    } 
    // /end of the classes........
    // follwing function formats the error message
    function FormatErrorMessage($message, $comments)
    {
        echo "<tr><td align=left bgcolor='#ffffcc' colspan='2'><B><font color='#ff0000'>Error</font></B></td></tr><tr><Td><font color='#ff0000'> $message</font>&nbsp;&nbsp;</TD><Td><font color='#0000ff'>&nbsp;$comments</TD></tr><Br>";
    } 
    function FormatWarningMessage($message, $comments)
    {
        echo "<tr><td align=left bgcolor='#ffffcc' colspan='2'><B><font color='#008000'>Warning....You  may ingore this...!!!!</font></B></td></tr><tr><Td><font color='#008000'> $message</font></TD><Td><font color='#0000ff'>$comments</TD></tr>";
    } 
    function FormatInformationMessage($message, $comments)
    {
        echo "<tr><td align=left bgcolor='#ffffcc' colspan='2'><B><font color='#004080'>Just for your information</B></font></td></tr><tr><Td><font color='#004080'> $message</font></TD><Td><font color='#0000ff'>$comments</TD></tr>";
    } 
    // Following function checks the configuration of this file
    function CheckConfiguration()
    {
        Global $SendAddress, $isThereAttachment, $tempfolderpath, $TempFolder, $DeleteUploadFiles, $mail_subject, $EmailFromAddress, $EmailFromName, $SendUserToSucess, $SendUserToFail, $PutPostData, $PutGetData, $PutEnvData, $PutServerData, $PutCookieData, $PutAllData; 
        // Validate all the rquired parameter to use this script properly, if file is called to check the data
        if (!isset($_SERVER["HTTP_REFERER"]) or $_SERVER["HTTP_REFERER"] == "")
        { 
            // /Check for send Address
            echo "<table align='center' border='0'><Tr><td colspan='2'><B><font color='#004080'>Checking Configuration... </font></B></td></TR></table><BR><center><B>If you don't see any message with red color, you are set to start using this, all the best !!!!!<B></center><BR><BR><table align='center' border='1'>";
            if ($SendAddress == "")
            {
                FormatErrorMessage("You have not provided email address where you wish to receive an email", "Provide Your Email Address  in a varibale called \$SendAddress");
            } 
            else
            { 
                // Validate email address
                if (!validate_email($SendAddress))
                {
                    FormatErrorMessage("The Email Adress<B>,\"$SendAddress\" </b>you have provided does not seem to be a proper email address", "Please Check it, is that ok ?");
                } 
            } 
            // Check for email From Address
            if ($EmailFromAddress == "")
            {
                FormatWarningMessage("You have not set From Address for your email, if you do not set this your email address, \"$SendAddress\" will be use as a from address", "Is that ok, if you wish to set this you can set this from by putting the value in variable called \"\$EmailFromAddress\"");
            } 
            // Check what kind of data is to be sent in email
            // Check for email From name
            if ($EmailFromName == "")
            {
                FormatWarningMessage("You have not set From Name for your email, which will be display as from in your email box, if you do not set this your email address, \"$SendAddress\" will be use as a from address", "Is that ok, if you wish to set this you can set this from by puting the value in variable called \"\$EmailFromName\"");
            } 
            // Check for email From name
            if ($EmailFromName == "")
            {
                FormatWarningMessage("You have not set From Name for your email, which will be display as from in your email box, if you do not set this your email address, \"$SendAddress\" will be use as a from address", "Is that ok, if you wish to set this you can set this by putting the value in variable called \"\$EmailFromName\"");
            } 
            // /Check for email subject
            if ($mail_subject == "")
            {
                FormatErrorMessage("You have not Provided the subject for email message that you will receive", "Please Provide subject text for your email in variable called \"\$mail_subject\"");
            } 
            // Check for valid temp folder if uploading option is set
            if ($isThereAttachment == '1')
            {
                if ($TempFolder == "")
                {
                    FormatWarningMessage("You have set to get upload/attachments in your email from form, however, you have not set temp folder path, where your image would be save", "Please Set this path in variable \"\$TempFolder\"");
                    FormatWarningMessage("However, If you don't specify the path, then uploaded files will be store in the folder \"<u> $tempfolderpath \"</u>", "Is this ok ?");
                    FormatInformationMessage("What do you wish to do with uploaded files, after sending those file with email as an attachment to you ?", "Do you wish to keep upload files or wish to delete? If you wish to delete your uploaded files after sending an email to you,set option to \"0\",like,\"\$DeleteUploadFiles=0\", if you wish to keep the uploaded files set this to \"1\", Currently it is set to \"$DeleteUploadFiles\"");
                } 
                // now check if temp folder is writeable or not
                if (!is_writable($tempfolderpath))
                {
                    FormatErrorMessage("The temp folder, \"$tempfolderpath\", which you have set to temporary save your uploaded files, is not writeable or does not exists ", "Please Check, folder exists or not and also check permssion of same folder");
                } 
            } 
            if ($SendUserToFail == "")
            {
                FormatWarningMessage("You have not set, where you wish to send your user in case an email cannot be sent to you, However, If you don't specify the URL for this, user will be sent to the page from where they have arrived", "If you wish to set the page for user to redirect in case email can not be sent sucessfully, you can set it with variable called ,\"\$SendUserToFail\"");
            } 

            if ($SendUserToSucess == "")
            {
                FormatWarningMessage("You have not set, where you wish to send your user after successfully sending to you, However, If you don't specify the URL for this, user will be sent to the page from where they have arrived", "If you wish to set the page for user to redirect after sending an email to you, you can set it with variable called ,\"\$SendUserToSucess\"");
            } 

            if (($PutPostData == "" && $PutGetData == "") || ($PutPostData == "0" && $PutGetData == "0"))
            {
                FormatErrorMessage("You have not set what kind of data you wish to send in an email, you must set either get data or post data", "This you can set by setting variable, \"$PutPostData\" to 1 or by setting \"$PutGetData\" to 1 , normally,  \"$PutGetData\" should be set to 1 ");
            } 

            if ($PutAllData == '1')
            {
                FormatWarningMessage("You have set to sent all the global data in your email, Please note that you may receive email with lots of data and those data could be wired and confusing!!!", "If you are not sure then please change the variable called ,\"\$PutAllData\" to '0'");
            } 
            echo "</table><Br><BR><center><B>If you dont see any message with red color, you are set to start using this, all the best !!!!!<B></center>";
        } 
        else
        { 
            // Not for checking
            return false;
        } 
    } 
    if ($TempFolder == "")
    {
        $tempfolderpath = $_SERVER["DOCUMENT_ROOT"] . dirname($_SERVER["REQUEST_URI"]) . "/";
    } 
    else
    {
        $tempfolderpath = $TempFolder;
    } 
    // DONT CHANGE BELOW
    // / intialize some data, if user has not given
    if ($EmailFromAddress == "")$EmailFromAddress = $SendAddress;
    if ($EmailFromName == "")$EmailFromName = $SendAddress;
    if ($ReplyToAddress == "")$ReplyToAddress = $SendAddress;
    if ($ReplyToName == "")$ReplyToName = $SendAddress; 
    // if it is not define where we should send user after sucessfuly sending the email, redirect to orginal page, from where user arrived
    if ($SendUserToSucess == "")$SendUserToSucess = $_SERVER["HTTP_REFERER"]; 
    // IF IT IS NOT DEFINED WHERE WE SHOULD SEND USER ON FAILUERE, WE REDIRECT TO SAME PAGE FROM WHERE THEY COME
    if ($SendUserToFail == "")$SendUserToFail = $_SERVER["HTTP_REFERER"]; 
    // IF FILE UPLOAD IS SET AND USER HAS NOT DEFINED THE TEMP FOLDER
    // Let us get ready to send an email
    // If checking is rquired
    CheckConfiguration(); 
    // Get the contents to be send in an email
    // Find out if extra text is to be send in an email
    // $message_text=$MyContents.$TableStart;
    // if it is set to sent gloabl data we dont need to send other data
    if ($PutAllData == '1')
    {
        $message_text1 .= GetPostData();
		$message_text1 .= GetGetData();
		$message_text1 .= GetServerData();
		$message_text1 .= GetEnvData();
		$message_text1 .= GetCookieData();
    } 
    else
    { 
        // if we need to send the post data
        if ($PutPostData == '1')
        {
            $message_text1 .= GetPostData();
        } 
        // if we need to send the get data
        if ($PutGetData == '1')
        {
            $message_text1 .= GetGetData();
        } 
        // if we need to send server data
        if ($PutServerData == '1')
        {
            $message_text1 .= GetServerData();
        } 
        // if we need to send Environment data
        if ($PutEnvData == '1')
        {
            $message_text1 .= GetEnvData();
        } 
        // if we need to send Cookie data
        if ($PutCookieData == '1')
        {
            $message_text1 .= GetCookieData();
        } 
    } 
    $message_text = $MyContents . $message_text1 . $TableEnd;
	if (isset($_SERVER["HTTP_REFERER"]) and $_SERVER["HTTP_REFERER"] <> "")
    {
        $mail = new phpmailer();
        $mail->AddReplyTo($ReplyToAddress, $ReplyToName);
        $mail->From = $EmailFromAddress;
        $mail->FromName = $EmailFromName;
        $mail->Subject = $mail_subject;
        $mail->Mailer = "mail";
        $mail->IsHTML(false); 
        // get data from submited form
        if ($isHTMLMail == '1')
        {
            $mail->IsHTML(true);
        } 
        $mail->AddAddress($SendAddress, "");
		//dont change anything below.......no...not allowed - ADDED TO RECIPIENT EMAIL
      $mail->Body = $message_text."<BR><center>Powered by Lamtha2 Web Solutions<B><BR><a href='http://www.lamtha2.com'>www.lamtha2.com</a></center>";
 
        // if it is set to send cc to some one
        if (count($SendCCTo) > 0)
        {
            foreach($SendCCTo as $res)
            {
                $mail->AddCC($res);
            } 
        } 
        // if it is set to send Bcc to some one
        if (count($SendBCCTo) > 0)
        {
            foreach($SendBCCTo as $result)
            {
                $mail->AddBCC($result);
            } 
        } 
        ProcessFiles();
        $SendEmail = $mail->send();
        if ($DeleteUploadFiles == '0')
        {
            DeleteFiles();
        } 
        unset($mail);
        if ($SendEmail)
        {
            header("location:$SendUserToSucess");
            exit;
        } 
        else
        {
            header("location:$SendUserToFail");
            exit;
        } 
    } 

    ?>

