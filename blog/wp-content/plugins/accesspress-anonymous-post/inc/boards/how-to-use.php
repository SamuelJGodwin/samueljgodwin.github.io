<div class="ap-tabs-board" id="board-how_to_use-settings" style="display: none;">
<div class="ap-tab-wrapper">
<p><?php _e('There are three main settings panels that will help you to setup the plugin and the frontend form properly.','accesspress-anonymous-post');?></p>
<dl>
  <dt><strong><?php _e('General settings','accesspress-anonymous-post');?></strong></dt>
  <dd><p><?php _e('In this tab you can customize all the settings regarding the form and plugin general settings.All the settings setups such as Form Title,Post Publish Status,Admin notification and many more can be customized from this panel.','accesspress-anonymous-post');?></p></dd>
    
  <dt><strong><?php _e('Form Settings','accesspress-anonymous-post');?></strong></dt>
  <dd><p><?php _e('In this tab you can customize all the detailed settings regarding the form.You can setup all the necessary fields that needs to be shown in the form for post submission.You can also setup the necessary labels for the form fields.','accesspress-anonymous-post');?></p></dd>
  
  <dt><strong><?php _e('Captcha settings','accesspress-anonymous-post');?></strong></dt>
  <dd><p><?php _e('In this tab you can customize all the settings regarding the form security options.You can either enable or disable the captcha, setup the label for the captcha field and also you can provide the custom message for captcha error.Though you can disable the captcha in the form but we suggest you to enable this for keeping your form more secure.','accesspress-anonymous-post');?></p></dd>
  
  <dt><strong><?php _e('Using Shortcode','accesspress-anonymous-post');?></strong></dt>
  <dd>
    <p>
     <?php _e(' For viewing the form in the front end, you can place ','accesspress-anonymous-post');?><br /><br />
      <input type="text" readonly="readonly" value="[ap-form]" onfocus="this.select();"/><br /><br />
       shortcode in  any page's content editor where you want to display the post submission form.Or if you want to use it in your template file then you can use <br /><br /><input type="text" readonly="readonly" value="&lt;?php echo do_shortcode('[ap-form]');?&gt;" onfocus="this.select();" style="width: 270px;"/></p>
      <p><?php _e('If you have kept the redirection url in the general settings tabs then once the form is submitted, the page will be redirected to the url that you have kept in that field.So to display your custom message after successful post submission in the redirected page, then please use','accesspress-anonymous-post');?> <br /><br />
      
      <input type="text" readonly="readonly" value="[ap-form-message redirect='Place the URL where you want to redirect when this page is accessed directly']" onfocus="this.select();" style="width:100%;"/> <br/><br/>inside the page editor   or <br /><br />
      
      <input type="text" readonly="readonly" value="&lt;?php echo do_shortcode('[ap-form-message redirect='Place the URL where you want to redirect when this page is accessed directly']');?&gt;" onfocus="this.select();" style="width:100%;"/><br /> <br />inside the template file  of the redirected page where you want to display the custom post submission message.If you won't place any url in the redirect url field , then the form will be submitted to the same page. So in that case the message will be automatically displayed in the top of the form just after the form title so you don't need to use shortcode for this case.
    </p>
    <p class="description"><?php _e("<b>Note: </b>Please don't copy paste the above shortcodes directly onto the visual editor.Please type or copy paste into the text version editor.",'accesspress-anonymous-post');?></p>
  </dd>  
  
</dl>


</div>
</div>