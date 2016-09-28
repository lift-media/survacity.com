<!-- Left Side Of Navbar -->
<li>
	<a href="/home" <?php echo (Request::segment(1)=='home')?'class="activeTop"':"";?>>Home</a>
</li>
<li>
	<a href="/manage-email-template" <?php echo (Request::segment(1)=='manage-email-template' || Request::segment(1)=='add-email-template' || Request::segment(1)=='edit-email-template')?'class="activeTop"':"";?>>Manage Email Template</a>
</li>
<li>
	<a href="/listed-contacts" <?php echo (Request::segment(1)=='listed-contacts' || Request::segment(1)=='add-contact' || Request::segment(1)=='edit-contact')?'class="activeTop"':"";?>>Listed Contacts</a>
</li>
<li>
	<a href="/import-contacts" <?php echo (Request::segment(1)=='import-contacts')?'class="activeTop"':"";?>>Import Contacts</a>
</li>
<li>
	<a href="/manage-emails" <?php echo (Request::segment(1)=='manage-emails' || Request::segment(1)=='schedule-send-emails')?'class="activeTop"':"";?>>Manage Emails</a>
</li>
