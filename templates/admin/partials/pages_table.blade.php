<div id="pages-table"></div>

<hr>

<button id="export-csv" class="button button-secondary">{!! __('Export CSV', 'elk-analytics') !!}</button>
<button id="export-xlsx" class="button button-secondary">{!! __('Export XLSX', 'elk-analytics') !!}</button>
<button id="export-pdf" class="button button-secondary">{!! __('Export PDF', 'elk-analytics') !!}</button>

<script>
  jQuery(function($) {
    const data = @json($data);

    let exportType = "";

    const table = new Tabulator("#pages-table", {
      data: data,
      layout: "fitColumns",
      columns: [
        {title: "{!! __('Post ID', 'elk-analytics') !!}", field: "post_id", sorter: "number", hozAlign: "center"},
        {title: "{!! __('Title', 'elk-analytics') !!}", field: "title", sorter: "string", hozAlign: "left"},
        {title: "{!! __('Post Type', 'elk-analytics') !!}", field: "type", sorter: "string", hozAlign: "center"},
        {
          title: "{!! __('Permalink', 'elk-analytics') !!}", 
          field: "permalink", 
          formatter: "link", 
          formatterParams: {target: "_blank"},
          hozAlign: "center"
        },
        {title: "{!! __('Page Views', 'elk-analytics') !!}", field: "page_views", sorter: "number", hozAlign: "center"}
      ]
    });

    document.getElementById("export-csv").addEventListener("click", () => {
      exportType = "csv";
      table.download("csv", "elk_analytics_pages.csv", { delimiter: ",", bom: true });
    });

    document.getElementById("export-xlsx").addEventListener("click", () => {
      exportType = "xlsx";
      table.download("xlsx", "elk_analytics_pages.xlsx", { sheetName: "{!! __('ELK Analytics Pages', 'elk-analytics') !!}" });
    });

    document.getElementById("export-pdf").addEventListener("click", () => {
      exportType = "pdf";
      table.download("pdf", "elk_analytics_pages.pdf", {
        orientation: "portrait",
        title: "{!! __('ELK Analytics Pages', 'elk-analytics') !!}"
      });
    });
  });
</script>
