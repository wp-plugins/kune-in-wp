<form id="kune_op_form" class="kune-mce-form"  method="post" action="">
  <div class="form_description">
    <p>Insert the URL of your kune document:</p>
  </div>
  <ul >
    <li id="li_1" >
      <label class="description" for="element_1">Url: </label>
      <div>
        <input id="element_1" name="element_1" class="element text medium" type="text" maxlength="255" value=""/>
      </div><p class="guidelines" id="guide_1"><small>http://kune.cc/#!sandbox.docs.922.758</small></p>
    </li>		<li id="li_4" >
      <label class="description" for="element_4">Options </label>
      <span>
        <input id="element_4_1" name="element_4_1" class="element checkbox" type="checkbox" value="1" checked="<?php echo $default_kune_readOnly? 'checked': '' ?>" />
        <label class="choice" for="element_4_1">Read only (edition disabled in WordPress)</label>
        <input id="element_4_2" name="element_4_2" class="element checkbox" type="checkbox" value="1" checked="<?php echo $default_kune_showSignIn?'checked':''; ?>"/>
        <label class="choice" for="element_4_2">Sign in visible?</label>
        <input id="element_4_3" name="element_4_3" class="element checkbox" type="checkbox" value="1" checked="<?php echo $default_kune_showSignOut?'checked':''; ?>"/>
        <label class="choice" for="element_4_3">Sign out visible?</label>

      </span>
    </li>		<li id="li_2" >
      <label class="description" for="element_2">Buttons top margin </label>
      <div>
        <input id="element_2" name="element_2" class="element text small" type="text" maxlength="100" value="<?php echo $default_kune_sitebarTopMargin; ?>"/>
      </div>
    </li>		<li id="li_3" >
      <label class="description" for="element_3">Buttons right margin </label>
      <div>
        <input id="element_3" name="element_3" class="element text small" type="text" maxlength="100" value="<?php echo $default_kune_sitebarTopMargin; ?>"/>
      </div>
    </li>

    <li class="buttons">
      <input type="hidden" name="form_id" value="1023042" />

      <input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
    </li>
  </ul>
</form>
