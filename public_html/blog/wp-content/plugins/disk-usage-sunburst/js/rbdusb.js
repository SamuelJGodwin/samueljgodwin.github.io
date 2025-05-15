( function( $ ) {
    function supportsSVG() {
        return !!document.createElementNS && !!document.createElementNS('http://www.w3.org/2000/svg', "svg").createSVGRect;
    }
    if (!supportsSVG()) {
        $('#svg_not_supported').html("This plugin depends on SVG. Unfortunately your browser does not support SVG. Please update to a modern browser...");
    }

    let width   = 960,
        height  = 720,
        radius  = Math.min(width, height) / 2;

    let x = d3.scale.linear()
        .range([0, 2 * Math.PI]);

    let y = d3.scale.sqrt()
        .range([0, radius]);

    let color = d3.scale.category20c();

    let svg = d3.select("#wpbody-content").append("svg")
        .attr("id", "rbdusb_svg")
        .attr("width", width)
        .attr("height", height)
        .append("g")
        .attr("transform", "translate(" + width / 2 + "," + (height / 2 + 10) + ")");

    let partition = d3.layout.partition()
        .value(function(d) { return d.size; });

    let arc = d3.svg.arc()
        .startAngle(function(d) { return Math.max(0, Math.min(2 * Math.PI, x(d.x))); })
        .endAngle(function(d) { return Math.max(0, Math.min(2 * Math.PI, x(d.x + d.dx))); })
        .innerRadius(function(d) { return Math.max(0, y(d.y)); })
        .outerRadius(function(d) { return Math.max(0, y(d.y + d.dy)); });

    let tooltip = d3.select("#wpbody-content").append("div")
        .attr("id", "rbdusb_tooltip")
        .attr("class", "tooltip")
        .style("opacity", 0);

    $.post(
        ajaxurl,
        { 'action': 'rbdusb_data' },
        function(rawData) {
            $('#rbdusb_loading').fadeOut( 50 , function() {
                    $('#rbdusb_info').fadeIn();
                });

            let root = JSON.parse(rawData);

            let path = svg.selectAll("path")
                .data(partition.nodes(root))
                .enter().append("path")
                .attr("d", arc)
                .style("fill", function(d) { return color((d.children ? d : d.parent).name); })
                .on("click", click)
                .on("mousemove", function(d) {
                    tooltip.transition().duration(200).style("opacity", .9);
                    tooltip.html(d.name)
                        .style("left", (d3.event.clientX + 20) + "px")
                        .style("top", (d3.event.clientY - 20) + "px");
                })
                .on("mouseout", function() {
                    tooltip.transition().duration(200).style("opacity", 0);
                });

            function click(d) {
                path.transition()
                    .duration(750)
                    .attrTween("d", arcTween(d));
            }
        }
    ).fail(function() {
        $('#rbdusb_loading').html('Unfortunately there was an error. File/directory sizes could not be determined. Reload to try again.');
    });

    d3.select(self.frameElement).style("height", height + "px");

    // Interpolate the scales!
    function arcTween(d) {
        let xd = d3.interpolate(x.domain(), [d.x, d.x + d.dx]),
            yd = d3.interpolate(y.domain(), [d.y, 1]),
            yr = d3.interpolate(y.range(), [d.y ? 20 : 0, radius]);
        return function(d, i) {
            return i
                ? function(t) { return arc(d); }
                : function(t) { x.domain(xd(t)); y.domain(yd(t)).range(yr(t)); return arc(d); };
        };
    }
} )( jQuery );