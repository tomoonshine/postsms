<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "ulang://common/modulelements">
  <xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="data" priority="1">
      <div class="imgButtonWrapper" xmlns:umi="http://www.umi-cms.ru/TR/umi">
        <a id="addCategory" href="{$lang-prefix}/admin/modulelements/add/{$param0}/groupelements/" class="type_select_gray" 
          umi:type="modulelements::groupelements">
          <xsl:text>&label-add-list;</xsl:text>
        </a>
        <a id="addObject" href="{$lang-prefix}/admin/modulelements/add/{$param0}/item_element/" class="type_select_gray" 
          umi:type="modulelements::item_element">
          <xsl:text>&label-add-item;</xsl:text>
        </a>
      </div>
      <xsl:call-template name="ui-smc-table">
        <xsl:with-param name="js-add-buttons">
          <![CDATA[ createAddButton($('#addCategory')[0], oTable, '{$pre_lang}/admin/modulelements/add/{id}/groupelements/', 
          ['groupelements', true]); createAddButton($('#addObject')[0], oTable, '{$pre_lang}/admin/modulelements/add/{id}/item_element/', ['groupelements']); ]]>
        </xsl:with-param>
      </xsl:call-template>
    </xsl:template>
  </xsl:stylesheet>