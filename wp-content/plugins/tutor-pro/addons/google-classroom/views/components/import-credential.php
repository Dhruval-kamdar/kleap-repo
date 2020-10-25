<div class="consent-screen oauth-redirect-url">
    Create OAuth access data and upload Credentials JSON from <a href="https://console.developers.google.com/" target="_blank"><b>Google Console</b></a>. 
    As a redirect URI set <b><?php echo get_home_url().'/'.\TUTOR_GC\init::$google_callback_string.'/'; ?></b>
</div>

<div class="consent-screen" id="tutor_gc_credential_upload">
    <div class="tutor-upload-area">
        <img src="<?php echo TUTOR_GC()->url; ?>/assets/images/upload-icon.svg"/>
        
        <h2>Drag & Drop your JSON File here</h2>

        <p><small>or</small></p>
        <button class="button button-primary">Browse File</button>

        <input type="file" name="credential" accept=".json"/>
    </div>
    <button type="submit" class="button button-primary button-large" disabled="disabled">
        Load Credentials
    </button>
</div>