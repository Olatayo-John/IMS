<div class="container">
<div id="myfirstchart" style="height: 250px;"></div>
<div id="something" style="height: 200px"></div>
</div>


<script type="text/javascript">
	new Morris.Donut({
  element: 'myfirstchart',
  data: [
    { label: '2008', value: 20 },
    { label: '2009', value: 10 },
    { label: '2010', value: 5 },
    { label: '2011', value: 5 },
    { label: '2012', value: 20 }
  ],
  // xkey: 'year',
  // ykeys: ['value'],
  // labels: ['Value']
});
</script>