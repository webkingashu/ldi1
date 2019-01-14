<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
    <title>UIDAI</title>
    <style>
    a {
      color: #000;
  }
  body{
      color: #000;
      line-height: 28px;
  }
</style>
</head>

<body style="background-color: #eee;">
    <table width="640" align="center" border="0" cellpadding="0" cellspacing="0">
      <tbody>
        <tr>
            <td align="center" valign="top" bgcolor="#1ab394"><!-- <img src="https://api.choicebroking.in/mf/files/imgs/forgot_psswd_banner.gif" width="640" alt=""/> --> <p style="font:400 18px 'Roboto', sans-serif; margin-top: 10px;color: #fff; text-align:center">UIDAI</p></td>
      </tr>
      <tr>
          <td align="left" valign="top" bgcolor="#28b06e" style="font-size: 12px; color: #000000; font-weight: normal;">
           <table width="640" border="0" align="center" cellpadding="0" cellspacing="0">
            <tbody>
              <tr>
                <td align="center" valign="top" bgcolor="#FFFFFF">
                  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                          <td height="20" colspan="4">&nbsp;</td>
                      </tr>
                      <tr>
                          <td width="60">&nbsp;</td>
                          <td align="center">
                            <p style="font:400 18px 'Roboto', sans-serif; margin-top: 0;">Hello </p>

                            <p style="font:300 14px 'Roboto',sans-serif;line-height:28px"><span style="font:700 14px 'Roboto', sans-serif;">You are receiving this email because we received a password reset request for your account.</span> </p>
                            {{-- Action Button --}}
                            @isset($actionText)
                            <?php
                            switch ($level) {
                                case 'success':
                                $color = 'green';
                                break;
                                case 'error':
                                $color = 'red';
                                break;
                                default:
                                $color = 'blue';
                            }
                            ?>
                            <p>{{ $actionUrl}}</p>
                            @component('mail::button', ['url' => $actionUrl, 'color' => $color])
                            {{ $actionText }}
                            @endcomponent
                            @endisset
                        </td>
                        <td width="60">&nbsp;</td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>

</tbody>
</table>
</td>
</tr>
<tr>
  <td align="left" valign="top" bgcolor="#f0f0f0">
    <table width="640" bgcolor="#fff" border="0" align="center" cellpadding="0" cellspacing="0">
      <tbody>
          <tr>
              <td width="60">&nbsp;</td>
              <?php if(isset($request->privious_status) && !empty($request->privious_status) && !empty($request->status_name) ) { ?>
                  <td align="center" bgcolor="#17cca7">

                    <p style="font:300 14px 'Roboto', sans-serif; color: #fff;">Status Changed from &nbsp;<?php echo (isset( $request->privious_status)) ? $request->privious_status : ''; ?> to <?php echo (isset( $request->status_name)) ? $request->status_name : ''; ?><span style="font:700 14px 'Roboto', sans-serif"></span></p>

                </td> 
            <?php }?> 
            <?php if(isset($request['otp']) && !empty($request['otp'])) { ?>
              <td align="center" bgcolor="#17cca7">

                <p style="font:300 14px 'Roboto', sans-serif; color: #fff;"></span><?php echo (isset( $request['otp'])) ? $request['otp'] : ''; ?><span style="font:700 14px 'Roboto', sans-serif"></span></p>

            </td> 
        <?php }?> 
        <td width="60">&nbsp;</td>
    </tr>
    <tr>
      <td width="60">&nbsp;</td>
      <td height="20" style="border-bottom: 1px solid #eee;">&nbsp;</td>
      <td width="60">&nbsp;</td>
  </tr>
  <tr>
   <td height="20" colspan="4">&nbsp;</td>
</tr>
<tr>
  <td width="60">&nbsp;</td>
  <td align="center" valign="top" bgcolor="#FFFFFF">
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
     <tbody>
       <tr>
        <td width="40%">

            <img src="https://api.choicebroking.in/mf/files/imgs/phone_icon.png" style="vertical-align: middle;">
            <a href="tel:+91-8080-80-8875" target="_blank" style="font:300 14px 'Roboto', sans-serif;  color:#000000;text-decoration: none; padding-left: 10px;">
                +91 9999999999
            </a>
        </td>

        <td width="60%">

            <img src="https://api.choicebroking.in/mf/files/imgs/mail_icon.png" style="vertical-align: middle;">
            <a href= "mailto:customercare@investica.com" target="_blank" style="font:300 14px 'Roboto', sans-serif; color:#000000;text-decoration: none; padding-left: 10px;">
               test@choicetechlab.com
           </a>
       </td>

   </tr>
</tbody>
</table>
</td>
<td width="60">&nbsp;</td>
</tr>
<tr>
  <td height="30">&nbsp;</td>
</tr>

</tbody>
</table>
</td>
</tr>
<tr>
  <td height="20" style="border-bottom: 1px solid #c9c0c0;">&nbsp;</td>
</tr>
<tr>
  <td align="left" valign="top" bgcolor="#eee">
    <table width="640" border="0" align="center" cellpadding="0" cellspacing="0">
      <tbody>
        <tr>
          <td height="20">&nbsp;</td>
      </tr>
      <tr>
          <td width="150">&nbsp;</td>
          <td align="center">
            <p style="color: #585151; font:400 14px 'Roboto', sans-serif;" >Love and Respect,</p>
            <p style="font:700 14px 'Roboto', sans-serif; color:#000000;">Team UIDAI </p>
        </td>
        <td width="150">&nbsp;</td>
    </tr>
    <tr>
     <td height="20">&nbsp;</td>
 </tr>
</tbody>

</table>
</td>
</tr>
</tbody>
</table>
</body>
</html>
