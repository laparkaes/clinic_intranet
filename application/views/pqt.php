<!DOCTYPE html>
<html lang="en">
<head>
<script src="<?= base_url() ?>resorces/vendor/global/global.min.js"></script>
<script>
async function start() {
	const url = 'https://kr.kompass.com/easybusiness/company/query/?CSRFToken=437d2ee6-3ebf-4180-82a3-bdd6be2d9c47';

	const data = '{"pageNumber":1,"pageSize":"50","sort":null,"criterias":[{"@type":"criteria","index":1,"code":"companyList","count":4952,"label":"all","layerId":null,"enabled":true,"active":true,"offset":0,"limit":null,"sort":null,"ids":["200323644899"],"countryCode":null,"ranges":[],"storeInSession":false,"family":false,"order":null}],"freeCount":false}';

	const response = await fetch(url, {
		method: 'POST',
		headers: {
			'Host': 'kr.kompass.com',
			'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/111.0',
			'Accept': 'application/json, text/plain, */*',
			'Accept-Language': 'ko-KR,ko;q=0.8,en-US;q=0.5,en;q=0.3',
			'Accept-Encoding': 'gzip, deflate, br',
			'X-NewRelic-ID': 'XA8BUFVaGwYIVlZVDgM=',
			'Content-Type': 'application/json',
			'Content-Length': '340',
			'Origin': 'https://kr.kompass.com',
			'Connection': 'keep-alive',
			'Referer': 'https://kr.kompass.com/easybusiness/',
			'Cookie': 'timezoneoffset=300; timezonename=America/Lima; datadome=7IwMRPCRIGfnBJIvkA5SxbfGPJ3CUtjHG5jkgoW5j0pboEdnOEDV4BrdDaGZXGsSEwndcUCyJ8MWqa8soGWEBfygsPUzHhxkh0b~oTqSvAMAUMvksx75EFmY3bAFTk~W; route=1679359672.502.36.713028|1ca372b33d2bad9524c20eaf607b64ca; JSESSIONID=01B173474CC2F92044BC25FFFCE4C8BD; _k_cty_lang=en_KR; kp_uuid=223a966a-94da-4984-b1b0-72629a884969; ROUTEID=.; axeptio_cookies={%22$$token%22:%22a3on45kc8vq9cizl2fhbsf%22%2C%22$$date%22:%222023-03-21T00:47:53.316Z%22%2C%22$$completed%22:false}; axeptio_authorized_vendors=%2C%2C; axeptio_all_vendors=%2C%2C; _gcl_au=1.1.1727085236.1679359673; _ga=GA1.3.1913626510.1679359674; _gid=GA1.3.2035161986.1679359674; timezoneoffset=300; acceleratorSecureGUID=269c256f5e7b8ce997eb8721081df2561eb79fe6; state=1; _ga=GA1.2.1913626510.1679359674; _gid=GA1.2.2035161986.1679359674; SnapABugRef=https%3A%2F%2Fkr.kompass.com%2Feasybusiness%2F%3Fj_force_login%3Don%26CSRFToken%3D306e0b51-77c6-474a-814f-b63ca988cda5%23%2F%20https%3A%2F%2Fkr.kompass.com%2F%3Fkick%3Dtrue; SnapABugHistory=1#; SnapABugVisit=2#1679359698; clientUuid=b9df729c-f1cd-43c5-b715-f531e344affc',
			'Sec-Fetch-Dest': 'empty',
			'Sec-Fetch-Mode': 'cors',
			'Sec-Fetch-Site': 'same-origin',
			'Pragma': 'no-cache',
			'Cache-Control': 'no-cache',
			'TE': 'trailers',
		},
		body: data,
	});

	const text = await response.text();

	console.log(text);
}

$(document).ready(function() {
	start();
});
</script>

</head>
<body>
	<div id="result"></div>
</body>
</html>