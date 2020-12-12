<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="html"/>

  <xsl:template match="/">
  
    <html>
      <head>
        <title>
          <xsl:value-of select="feed/title" />
        </title>

      </head>
      <body>

          <h1>
            <a>
              <xsl:attribute name="href">
                <xsl:value-of select="feed/link" />
              </xsl:attribute>
              <xsl:value-of select="feed/title" />
            </a>
          </h1>
          
          <div id="content">
            <xsl:apply-templates/>
          </div>
                
          <ul>
            <xsl:for-each select="feed/entry">
              <li>
                <a>
                  <xsl:attribute name="href">#<xsl:number/></xsl:attribute>
                  <xsl:value-of select="title"/>
                </a>
              </li>                   
            </xsl:for-each>
          </ul>

      </body>
    </html>
  </xsl:template>
  
  <xsl:template match="text()">
    <!-- This hides all the extra junk that we don't want -->
  </xsl:template> 
  
  
  <xsl:template match="entry">
    
    <h3>
      <a>
        <xsl:attribute name="name">
          <xsl:number/>
        </xsl:attribute>            
        <xsl:attribute name="href">
          <xsl:value-of select="./link" />
        </xsl:attribute>
        <xsl:value-of select="./title" />
      </a>
    </h3>

    <xsl:if test="./updated">
      <p class="author">
        Link: 
        <b>
          <a>         
            <xsl:attribute name="href">
              <xsl:value-of select="./link" />
            </xsl:attribute>
            <xsl:value-of select="./link" />
          </a>
        </b>
      </p>
      <br />
      <p class="date">Posted on: <b><xsl:value-of select="./updated"/></b></p>
    </xsl:if>

    <xsl:choose>
      <xsl:when test="./description">
            <xsl:value-of select="./description"/>
      </xsl:when>
      <xsl:otherwise>
        <p>
          Read it at <a>          
            <xsl:attribute name="href">
              <xsl:value-of select="./link" />
            </xsl:attribute>
            <xsl:value-of select="./link" />
          </a>
        </p>
      </xsl:otherwise>
    </xsl:choose>
    
  </xsl:template>

</xsl:stylesheet>
