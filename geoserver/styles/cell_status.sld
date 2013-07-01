<?xml version="1.0" encoding="ISO-8859-1"?>
<StyledLayerDescriptor version="1.0.0" xmlns="http://www.opengis.net/sld" xmlns:ogc="http://www.opengis.net/ogc"
  xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://www.opengis.net/sld http://schemas.opengis.net/sld/1.0.0/StyledLayerDescriptor.xsd">
  <NamedLayer>
    <Name>Default Polygon</Name>
    <UserStyle>
      <Title>Default polygon style</Title>
      <Abstract>A sample style that just draws out a solid gray interior with a black 1px outline</Abstract>
      <FeatureTypeStyle>

        <Rule>
          <Title>No reported occurence</Title>
          <ogc:Filter>
            <ogc:PropertyIsEqualTo>
              <ogc:PropertyName>status</ogc:PropertyName>
              <ogc:Literal>1</ogc:Literal>
            </ogc:PropertyIsEqualTo>
          </ogc:Filter>          
          <PolygonSymbolizer>
            <Fill>
              <CssParameter name="fill">#CDE8F2</CssParameter>
              <CssParameter name="opacity">0.5</CssParameter>              
            </Fill>
          </PolygonSymbolizer>
        </Rule>

        <Rule>
          <Title>Proposed</Title>
          <ogc:Filter>
            <ogc:PropertyIsEqualTo>
              <ogc:PropertyName>status</ogc:PropertyName>
              <ogc:Literal>2</ogc:Literal>
            </ogc:PropertyIsEqualTo>
          </ogc:Filter>          
          <PolygonSymbolizer>
            <Fill>
              <CssParameter name="fill">#FECEA0</CssParameter>
              <CssParameter name="opacity">0.5</CssParameter>              
            </Fill>
          </PolygonSymbolizer>
        </Rule>
        
        <Rule>
          <Title>Present</Title>
          <ogc:Filter>
            <ogc:PropertyIsEqualTo>
              <ogc:PropertyName>status</ogc:PropertyName>
              <ogc:Literal>3</ogc:Literal>
            </ogc:PropertyIsEqualTo>
          </ogc:Filter>          
          <PolygonSymbolizer>
            <Fill>
              <CssParameter name="fill">#E77576</CssParameter>
              <CssParameter name="opacity">0.5</CssParameter>              
            </Fill>
          </PolygonSymbolizer>
        </Rule>        
        
      </FeatureTypeStyle>
    </UserStyle>
  </NamedLayer>
</StyledLayerDescriptor>