<?php
	// get speed, lat and lon maxes and mins for color scale and map bounds
	$speeds = array();
	$lats = array();
	$lons = array();
	foreach ($nodes as $n) {
		$speeds[] = $n['speed'];
		$lats[] = $n['lat'];
		$lons[] = $n['lon'];
	}
	$coloroptions = array(max($speeds),min($speeds));
	function speedcolor($speed,$c) {
		$colors = array('#ffd700','#ffbf1e','#ffa636','#fb8c46','#f4734f','#e95c50','#db444c','#ca2f43','#b81a33','#a2071f','#8b0000');
		$level = round((($speed-$c[1])/($c[0]-$c[1]))*10,0);
		return $colors[$level];
	}
	$bounds = array(
		'min' => min($lons).', '.min($lats),
		'max' => max($lons).', '.max($lats)
	);

?>{
  "responses": [
    {
      "landmarkAnnotations": [
        {
          "mid": "/m/0ly9g",
          "description": "Grand Place",
          "score": 0.88306844,
          "boundingPoly": {
            "vertices": [
              {
                "x": 500,
                "y": 267
              },
              {
                "x": 2867,
                "y": 267
              },
              {
                "x": 2867,
                "y": 1522
              },
              {
                "x": 500,
                "y": 1522
              }
            ]
          },
          "locations": [
            {
              "latLng": {
                "latitude": 50.846679,
                "longitude": 4.352517
              }
            }
          ]
        },
        {
          "mid": "/m/0ly9g",
          "description": "Grand Place, Brussels Town Hall",
          "score": 0.7675898,
          "boundingPoly": {
            "vertices": [
              {
                "x": 563,
                "y": 432
              },
              {
                "x": 2453,
                "y": 432
              },
              {
                "x": 2453,
                "y": 1687
              },
              {
                "x": 563,
                "y": 1687
              }
            ]
          },
          "locations": [
            {
              "latLng": {
                "latitude": 50.846625,
                "longitude": 4.352517
              }
            }
          ]
        }
      ],
      "labelAnnotations": [
        {
          "mid": "/m/0dvh9",
          "description": "classical architecture",
          "score": 0.9277685,
          "topicality": 0.9277685
        },
        {
          "mid": "/m/05_5t0l",
          "description": "landmark",
          "score": 0.9201572,
          "topicality": 0.9201572
        },
        {
          "mid": "/m/056mk",
          "description": "metropolis",
          "score": 0.9015841,
          "topicality": 0.9015841
        },
        {
          "mid": "/m/01n32",
          "description": "city",
          "score": 0.8909165,
          "topicality": 0.8909165
        },
        {
          "mid": "/m/0cgh4",
          "description": "building",
          "score": 0.8861227,
          "topicality": 0.8861227
        },
        {
          "mid": "/m/01wkk9",
          "description": "town square",
          "score": 0.8843372,
          "topicality": 0.8843372
        },
        {
          "mid": "/m/0dx1j",
          "description": "town",
          "score": 0.8816737,
          "topicality": 0.8816737
        },
        {
          "mid": "/m/01klb9",
          "description": "plaza",
          "score": 0.87239355,
          "topicality": 0.87239355
        },
        {
          "mid": "/m/05zp8",
          "description": "palace",
          "score": 0.8471053,
          "topicality": 0.8471053
        },
        {
          "mid": "/m/039jbq",
          "description": "urban area",
          "score": 0.82765996,
          "topicality": 0.82765996
        }
      ],
      "imagePropertiesAnnotation": {
        "dominantColors": {
          "colors": [
            {
              "color": {
                "red": 119,
                "green": 118,
                "blue": 116
              },
              "score": 0.29968154,
              "pixelFraction": 0.1934
            },
            {
              "color": {
                "red": 238,
                "green": 244,
                "blue": 251
              },
              "score": 0.06477208,
              "pixelFraction": 0.16253333
            },
            {
              "color": {
                "red": 86,
                "green": 85,
                "blue": 85
              },
              "score": 0.27728543,
              "pixelFraction": 0.17193334
            },
            {
              "color": {
                "red": 55,
                "green": 54,
                "blue": 56
              },
              "score": 0.11082739,
              "pixelFraction": 0.121133335
            },
            {
              "color": {
                "red": 148,
                "green": 148,
                "blue": 147
              },
              "score": 0.10743764,
              "pixelFraction": 0.1214
            },
            {
              "color": {
                "red": 29,
                "green": 26,
                "blue": 27
              },
              "score": 0.042825326,
              "pixelFraction": 0.075333335
            },
            {
              "color": {
                "red": 122,
                "green": 121,
                "blue": 102
              },
              "score": 0.023733435,
              "pixelFraction": 0.006
            },
            {
              "color": {
                "red": 55,
                "green": 56,
                "blue": 77
              },
              "score": 0.016162071,
              "pixelFraction": 0.0048
            },
            {
              "color": {
                "red": 94,
                "green": 91,
                "blue": 73
              },
              "score": 0.013677315,
              "pixelFraction": 0.0025333334
            },
            {
              "color": {
                "red": 73,
                "green": 75,
                "blue": 94
              },
              "score": 0.011747767,
              "pixelFraction": 0.004333333
            }
          ]
        }
      },
      "cropHintsAnnotation": {
        "cropHints": [
          {
            "boundingPoly": {
              "vertices": [
                {},
                {
                  "x": 3007
                },
                {
                  "x": 3007,
                  "y": 2007
                },
                {
                  "y": 2007
                }
              ]
            },
            "confidence": 1,
            "importanceFraction": 0.35
          }
        ]
      }
    }
  ]
}
