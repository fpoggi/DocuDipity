<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:fn="http://www.w3.org/2005/xpath-functions"
    xmlns:pt="http://www.cs.unibo.it/patternstool"
    exclude-result-prefixes="xs fn pt" version="2.0">

    <xsl:output method="html"/>
    
    <xsl:variable name="patternsMap" select="pt:getSourceContent('patterns.xml')"/>

    <xsl:template match="/">
        <html>
            <head>
                <link type="text/css" href="patternsHTML.css" rel="stylesheet"/>
                <script type="text/javascript" src="resources/prototype1.6.1.js"></script>
                <script type="text/javascript" src="resources/scriptaculous/scriptaculous.js"></script>
            </head>
            <body>
                <xsl:call-template name="buildTOC">
                    <xsl:with-param name="rootContainer" select="."/>
                </xsl:call-template>
                <xsl:call-template name="buildIndexOfTerms"/>
                <xsl:call-template name="buildIML">
                    <xsl:with-param name="rootContainer" select="."/>
                </xsl:call-template>
            </body>
        </html>
            
    </xsl:template>
    


    <!-- ============================================================== -->
    <!--                                TOC                             -->
    <!-- ============================================================== --> 
    <xsl:template name="buildTOC">
        <xsl:param name="rootContainer"/>
        
        <div id="mainContainerTOC">
            <div id="mainContainerTitleTOC" onclick="Effect.toggle('mainContainerEntriesTOC', 'blind'); return false;">Table of Content</div>
            <div id="mainContainerEntriesTOC" style="display:none;">
                <xsl:apply-templates select="$rootContainer/*" mode="toc"/>
            </div>
        </div>
    </xsl:template>
    
    <xsl:template match="*" mode="toc">
        
        <xsl:choose>
            <xsl:when test="pt:isHeadedContainer(name()) = true()">
                <div class="headedContainerTOC">
                    <xsl:variable name="titleLabel" select="pt:getHeadedContainerHead(.)"></xsl:variable>
                    <div class="headedContainerTitleTOC">
                        <a href="#{pt:getIDforString($titleLabel)}ANCHOR" name="{pt:getIDforString($titleLabel)}TOC">
                        <xsl:value-of select="$titleLabel"/>
                        </a>    
                    </div>
                    <xsl:apply-templates select="*" mode="#current"/>
                </div>
            </xsl:when>
            <xsl:otherwise>
                <xsl:apply-templates select="*" mode="#current"/>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>



    <!-- ============================================================== -->
    <!--                       Index of Terms                           -->
    <!-- ============================================================== -->    
    <xsl:template name="buildIndexOfTerms">
        
            
        <xsl:variable name="inputTree" select="."/>
        
        <div id="mainContainerTERMS">
            <div id="mainContainerTitleTERMS" onclick="Effect.toggle('mainContainerEntriesTERMS', 'blind'); return false;">Index of Terms</div>
            <div id="mainContainerEntriesTERMS" style="display:none;">
            <xsl:for-each select="('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','x','y','z')">
            <xsl:variable name="currentLetter" select="."/>
            <div class="letterContainerTERMS">
            <div class="letterlableTERMS"><xsl:value-of select="$currentLetter"/></div>
                <xsl:for-each select="$patternsMap//atom/gid//text() | $patternsMap//inline/gid//text()">
            <xsl:variable name="currentAtom" select="." as="text()"/>    
                
            <xsl:variable name="atomTexts" select="$inputTree//*[name() = $currentAtom][not(*)]"/>
            

            <xsl:for-each-group select="$atomTexts" group-by="fn:normalize-space(text())">
                <xsl:variable name="s" select="current-grouping-key()"/>
                <xsl:if test="
                    starts-with(fn:lower-case($s),$currentLetter) and 
                    not(starts-with(fn:lower-case($s),'http://')) and 
                    (string-length($s) &gt; 1) and
                    (string-length($s) &lt; 50)">
                    <div class="singletermTERMS"><xsl:value-of select="$s"></xsl:value-of></div>
                </xsl:if>
            </xsl:for-each-group>    
                    

            </xsl:for-each>
            </div>
        </xsl:for-each>
        
            </div>
        </div>
        
    </xsl:template>

    <!-- ================================================== -->
    <!--                    Utils                           -->
    <!-- ================================================== -->    
    <xsl:function name="pt:getHeadedContainerHead">
        <xsl:param name="elementClone"></xsl:param>
        
 
        <xsl:variable name="headList" select="$patternsMap//headedContainer/gid[descendant::text() = name($elementClone)]/@headList"/>
        
        <xsl:variable name="headListFirstElementName" select="fn:tokenize($headList,',')[1]"/>        
        
        <xsl:value-of select="$elementClone/*[name() = $headListFirstElementName]/text()"/>
    </xsl:function>
    
    
    <xsl:function name="pt:isHeadedContainer">
        <xsl:param name="elementName"></xsl:param>
        
        <xsl:value-of select="$patternsMap//headedContainer/gid//text() = $elementName"/>
    </xsl:function>
    

    <xsl:function name="pt:isPopup">
        <xsl:param name="elementName"></xsl:param>
        
        <xsl:value-of select="$patternsMap//popup/gid//text() = $elementName"/>
    </xsl:function>
    
    
    <xsl:function name="pt:isMeta">
        <xsl:param name="elementName"></xsl:param>
        
        <xsl:value-of select="$patternsMap//meta/gid//text() = $elementName"/>
    </xsl:function>
    
    <xsl:function name="pt:getSourceContent">
        <xsl:param name="source-name"/>
        <xsl:sequence select="doc($source-name)"/>
    </xsl:function>
    
    <xsl:function name="pt:getIDforString">
        <xsl:param name="str"></xsl:param>
        <xsl:value-of select="translate($str,'% ,.-#','')"></xsl:value-of>
    </xsl:function>
    <!-- ============================================================== -->
    <!--                                IML                             -->
    <!-- ============================================================== --> 
    <xsl:template name="buildIML">
        <xsl:param name="rootContainer"/>
        
        <div id="mainContainerIML">
            <xsl:apply-templates mode="iml" select="$rootContainer"/>
        </div>
    </xsl:template>
    
    <xsl:template match="*[pt:isPopup(name()) = true()]" mode="iml">

                <xsl:variable name="localID" select="generate-id(.)"/>
                
                <div class="popupBox">
                    
                    <div class='popupLabel'  onmouseover="Effect.toggle('{$localID}', 'blind'); return false;" onmouseout="Effect.toggle('{$localID}', 'blind'); return false;">[ <xsl:value-of select="name()"/> ]</div>
                    <div id="{$localID}" style="display: none;">                
                        <div class="{name()}">
                            <xsl:apply-templates select="* | @* | text()" mode="#current"/>
                        </div>                
                    </div> 
                    
                </div>        
    </xsl:template>
    

    <xsl:template match="*[pt:isMeta(name()) = true()]" mode="iml">
        
        <xsl:variable name="localID" select="generate-id(.)"/>
        
        <div class="metaBox">
            <img src="resources/manbluflag.jpg" class="metaImage"
                onclick="Effect.toggle('{$localID}', 'blind'); return false;"
                alt="Marker: click here for details..."
                title="Marker: click here for details..."
            />
            
            <div id="{$localID}" class="metaSource" style="display:none;">                
                &lt;<xsl:value-of select="name()"/>
                <xsl:for-each select="@*">
                    <span class="metaAttribute">
                        <xsl:value-of select="name()"/>=&apos;<xsl:value-of select="."></xsl:value-of>&apos;
                    </span>
                </xsl:for-each>
                &gt;
            </div> 
            
        </div>        
    </xsl:template>
    
    
    

    <xsl:template match="*" mode="iml">
         
        <xsl:if test="pt:isHeadedContainer(name()) = true()">
             <xsl:variable name="titleLabel" select="pt:getHeadedContainerHead(.)"/>
             <a name="{pt:getIDforString($titleLabel)}ANCHOR" href="#mainContainerTitleTOC" class="anchor">toc</a>
         </xsl:if>
        <div class="{name()}">
            <xsl:apply-templates select="* | @* | text()" mode="#current"/>
        </div>        
    </xsl:template>


    <xsl:template match="@* | text()" mode="iml">
        <xsl:copy/>        
    </xsl:template>
    
    <xsl:template match="comment()" mode="iml"/>
    
</xsl:stylesheet>
