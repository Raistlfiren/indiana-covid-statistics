import React, { useState, useEffect, useRef, useMemo } from 'react';
import * as d3 from 'd3';
import axios from "axios";
//import * as topojson from 'topojson-client/dist/topojson-client';
import { feature } from "topojson-client";
import { geoAlbers, geoPath } from "d3-geo";
import Text from "react-svg-text";
import indianaCounties from "../../../../Resources/tl_2010_18_county10.json";

const width = 1000,
    height = 800

const projection = geoAlbers()
    .scale(10000)
    .translate([width / 2, height / 2 ])
    .center([0,39.766028])
    .rotate([86.441278,0])

const IndianaCountyMap = () => {
    const [geographies, setGeographies] = useState([]);
    const [extras, setExtras] = useState([]);

    useEffect(() => {
        axios.get('http://localhost/api/indiana')
            .then(result => {
                setExtras(result.data.extra)
                setGeographies(feature(result.data, result.data.objects.tl_2010_18_county10).features)
            })
            .catch(error => {
                console.log(error)
            });
    }, [])

    const handleCountyClick = e => {
        console.log(e)
    }

    const findCountyDaily = (countyName, data) => {
        console.log(data, countyName)
        data.filter(function(value){
            return value.countyName === countyName;
        })
    }

    return (
        <svg width={ width } height={ height } viewBox={ `0 0 ${width} ${height}`}>
            <g className="countries">
                {
                    geographies.map((d,i) => (
                        <g key={`feature-${i}`}>
                            <path
                                id={`path-${i}`}
                                key={`path-${i}`}
                                d={geoPath().projection(projection)(d)}
                                className="country"
                                fill={`rgba(38,50,56,${1 / geographies.length * i})`}
                                stroke="#333333"
                                strokeWidth={0.5}
                                onClick={() => handleCountyClick(d)}
                            />
                            {/*<text*/}
                            {/*    x={geoPath().projection(projection).centroid(d)[0]}*/}
                            {/*    y={geoPath().projection(projection).centroid(d)[1]}*/}
                            {/*    textAnchor="middle"*/}
                            {/*    fill="red"*/}
                            {/*    // width={25}*/}
                            {/*>*/}
                            {/*    <tspan>{ d.properties.NAME10 }</tspan>*/}
                            {/*</text>*/}
                            <Text
                                x={geoPath().projection(projection).centroid(d)[0]}
                                y={geoPath().projection(projection).centroid(d)[1]}
                                textAnchor="middle"
                                fill="red"
                                scaleToFit={true}
                                width={25}
                            >
                                { d.properties.NAME10 }
                            </Text>
                            {/*<Text*/}
                            {/*    x={geoPath().projection(projection).centroid(d)[0]}*/}
                            {/*    y={geoPath().projection(projection).centroid(d)[1]}*/}
                            {/*    textAnchor="middle"*/}
                            {/*    fill="red"*/}
                            {/*    scaleToFit={true}*/}
                            {/*    width={25}*/}
                            {/*>*/}
                            {/*    500*/}
                            {/*</Text>*/}
                            {/*<Text*/}
                            {/*    x={geoPath().projection(projection).centroid(d)[0]}*/}
                            {/*    y={geoPath().projection(projection).centroid(d)[1]}*/}
                            {/*    textAnchor="middle"*/}
                            {/*    fill="red"*/}
                            {/*    scaleToFit={true}*/}
                            {/*    width={25}*/}
                            {/*>*/}
                            {/*    1000*/}
                            {/*</Text>*/}
                        </g>
                    ))
                }
            </g>
        </svg>
    );
};

export default IndianaCountyMap;
// class IndianaCountyMap extends React.Component {
//
//     constructor(props){
//         super(props)
//         // this.createIndiana = this.createIndiana.bind(this)
//         this.state = {
//             isLoading: false,
//             jsonMap: null
//         }
//     }
//
//     componentDidMount() {
//         this.createIndiana()
//     }
//
//     componentDidUpdate() {
//         // this.createIndiana()
//     }
//
//     createIndiana()
//     {
//         axios.get('http://localhost/api/indiana')
//             .then(result => this.setState({
//                 jsonMap: result.data,
//                 isLoading: true
//             }))
//             .catch(error => this.setState({
//                 error,
//                 isLoading: false
//             }));
//     }
//
//     createMap()
//     {
//         const ref = useRef()
//
//         useEffect(() => {
//             const svgElement = d3.select(ref.current)
//             svgElement.append("circle")
//                 .attr("cx", 150)
//                 .attr("cy", 70)
//                 .attr("r",  50)
//         }, [])
//
//         return (
//             <svg
//                 ref={ref}
//             />
//         )
//     }
//
//     //
//     // useEffect(() => {
//     //         const svgElement = d3.select(ref.current)
//     //         svgElement.append("circle")
//     //     .attr("cx", 150)
//     //     .attr("cy", 70)
//     //     .attr("r",  50)
//     //     }, [])
//
//     render() {
//
//         if (this.state.isLoading) {
//             let topoData = this.state.jsonMap
//             let geoJson = topojson.feature(topoData, topoData.objects.tl_2010_18_county10)
//             let renderData = ({
//                 width: 500,
//                 height: 500,
//                 margin: 50,
//             })
//
//             const svg = d3
//                 .create('svg')
//                 .attr('width', renderData.width)
//                 .attr('height', renderData.height);
//
//             const clippedWidth = renderData.width - renderData.margin * 2;
//             const clippedHeight = renderData.height - renderData.margin * 2;
//
//             const geoMercator = d3
//                 .geoMercator()
//                 // the center uses longtitude and latitude
//                 // get Long/Lat data from google maps
//                 .center([128, 36])
//                 .fitSize([clippedWidth, clippedHeight], geoJson);
//
//             const pathGen = d3.geoPath(geoMercator);
//
//             const stage = svg
//                 .append('g')
//                 .attr('transform', `translate(${renderData.margin},${renderData.margin})`);
//
//             const textX = 10;
//             const textY = 10;
//             const infoText = stage
//                 .append('g')
//                 .attr('transform', `translate(${textX},${textY})`)
//                 .append('text');
//             infoText.text('no data');
//
//             stage
//                 .selectAll('.geopath')
//                 .data(geoJson.features)
//
//             svg.node()
//         }
//
//         return (
//             <svg
//                 ref={ref}
//             />
//         );
//     }
// }
//
// export default IndianaCountyMap;