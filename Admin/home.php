<?php
include 'view/header_admin.php';
// Chỉ cho phép admin xem dashboard, nhân viên chuyển hướng về ql_phieuXNKho
if (isset($_SESSION['Loai']) && $_SESSION['Loai'] == 1) {
    header("Location: index.php?action=ql_phieuXNKho");
    exit();
}
$year = date('Y');
if (!function_exists('get_soluong_phieu_thang')) {
    function get_soluong_phieu_thang($loai, $month, $year) {
        global $db;
        $query = "SELECT COUNT(ct.id) as total
                  FROM phieu_chitiet ct
                  INNER JOIN phieu p ON ct.id_phieu = p.id
                  WHERE p.loai_phieu = :loai
                    AND MONTH(p.ngay) = :month
                    AND YEAR(p.ngay) = :year";
        $statement = $db->prepare($query);
        $statement->bindValue(':loai', $loai);
        $statement->bindValue(':month', $month);
        $statement->bindValue(':year', $year);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return (int)$result['total'];
    }
}
// Lấy dữ liệu cho 12 tháng
$data_nhap = [];
$data_xuat = [];
for ($m = 1; $m <= 12; $m++) {
    $data_nhap[] = get_soluong_phieu_thang('nhap', $m, $year);
    $data_xuat[] = get_soluong_phieu_thang('xuat', $m, $year);
}
?>
<style>
	/* ======= Giao diện CSS cho trang quản lý phiếu xuất nhập kho ======= */
	.panel {
		border-radius: 12px !important;
		box-shadow: 0 2px 8px rgba(40, 62, 81, 0.07);
		border: none;
	}

	.panel-heading {
		border-radius: 12px 12px 0 0 !important;
		background: #e3eaf1 !important;
	}

	.panel-body {
		background: #fff;
		border-radius: 0 0 12px 12px;
		padding: 18px 18px 12px 18px;
	}

	.table {
		border-radius: 12px !important;
		overflow: hidden;
		background: #fafbfc;
		margin-bottom: 0;
	}

	.table>thead>tr {
		background: #e3eaf1;
	}

	.table>tbody>tr:hover {
		background: #f1f7fa;
		transition: background 0.2s;
	}

	.table>tbody>tr>td,
	.table>thead>tr>th {
		padding: 8px 10px !important;
		vertical-align: middle !important;
	}

	.form-group label {
		font-weight: 500;
	}

	.form-control {
		border-radius: 8px;
		border: 1px solid #cfd8dc;
		background: #f8fafb;
	}

	#ds_sanpham .sanpham-row {
		margin-bottom: 8px;
	}

	.btn-primary,
	.btn-warning,
	.btn-danger,
	.btn-default {
		border-radius: 8px !important;
	}

	.tittle h3 {
		color: #1a2a3a;
	}

	@media (max-width: 991px) {
		.panel-body {
			padding: 12px 6px 8px 6px;
		}
	}
</style>

<div class="right-col col-lg-10 col-md-9 col-sm-9 col-xs-12" style="background-color:#F7F7F7;color:#73879C;min-height:920px;">
	<!-- Vùng tiêu đề dashboard -->
	<div class="tittle">
		<h3>Dashboard</h3>
	</div>
	<!-- Vùng thống kê sản phẩm -->
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<div class="mini-content">
			<div class="title">
				<h2>Thống kê sản phẩm</h2>
			</div>
			<div class="main-mini-content">
				<table class="table table-striped" id="center-content">
					<thead>
						<tr>
							<th>Nhóm sản phẩm</th>
							<th>Hình ảnh</th>
							<th>Số lượng</th>
						</tr>
					</thead>
					<?php foreach ($categories as $category) : ?>
						<tr>
							<td>
								<a href="?action=ql_sp&category_id=<?php echo $category['IdNhomSP']; ?>&hangsx=0"><?php echo $category['TenNhomSP']; ?></a>
							</td>
							<td>
								<?php
								// Nếu HinhNSP đã có dấu '/' thì giữ nguyên, nếu không thì thêm 'nhomsp/'
								$imgFile = ltrim($category['HinhNSP'], '/');
								if (strpos($imgFile, 'nhomsp/') !== 0) {
									$imgFile = 'nhomsp/' . $imgFile;
								}
								$imgPath = "../images/" . $imgFile;
								if (!empty($category['HinhNSP']) && file_exists($imgPath)) {
								?>
									<img style="width:100px;" src="<?php echo $imgPath; ?>">
								<?php
								} else {
								?>
									<img style="width:100px;" src="../images/no-image.png" alt="No image">
								<?php
								}
								?>
							</td>
							<td>
								<p></p><?php get_soluong_sanpham($category['IdNhomSP']); ?> sản phẩm
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
			<!-- Vùng biểu đồ tỷ lệ sản phẩm -->
			<div class="title">
				<h2>Tỷ lệ %</h2>
			</div>
			<div class="main-mini-content">
				<script type="text/javascript">
					// Biểu đồ Pie Chart thống kê sản phẩm theo nhóm
					google.charts.load('current', {
						'packages': ['corechart']
					});
					google.charts.setOnLoadCallback(drawChart);

					function drawChart() {
						var data = new google.visualization.DataTable();
						data.addColumn('string', 'Topping');
						data.addColumn('number', 'Sản phẩm');
						data.addRows([
							<?php foreach ($categories1 as $category) : ?>['<?php echo $category['TenNhomSP']; ?>', <?php get_soluong_sanpham($category['IdNhomSP']); ?>],
							<?php endforeach; ?>
						]);
						var options = {
							'width': 0,
							'height': 350,
							is3D: true
						};
						var chart = new google.visualization.PieChart(document.getElementById('chart_sp'));
						chart.draw(data, options);
					}
				</script>
				<div id="chart_sp">
				</div>
			</div>
		</div>
	</div>
	<!-- Vùng thống kê phiếu xuất nhập -->
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<div class="mini-content">
			<div class="title">
				<h2>Thống kê Phiếu Xuất Nhập (năm <?php echo $year; ?>)</h2>
			</div>
			<div class="main-mini-content" style="padding-bottom:0;">
				<!-- Nút thu gọn bảng -->
				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
					<h3 style="margin: 0;">Bảng Phiếu Xuất Nhập</h3>
					<button id="toggle-table-phieu" style="border: none; background: none; cursor: pointer;">
						<span id="arrow-icon" style="display: inline-block; transition: transform 0.2s;">&#9660;</span>
					</button>
				</div>
				<!-- Bảng nằm trong khung bo góc, nền sáng, có thể thu gọn -->
				<div id="table-phieu-xuat-nhap" style="border-radius:12px; overflow:hidden; background:#fafbfc; box-shadow:0 2px 8px rgba(40,62,81,0.07); margin-bottom:0; transition:height 0.2s;">
					<table class="table table-striped" id="center-content" style="margin-bottom:0;">
						<thead>
							<tr>
								<th>Tháng</th>
								<th>Phiếu nhập</th>
								<th>Phiếu xuất</th>
							</tr>
						</thead>
						<?php for ($m = 1; $m <= 12; $m++): ?>
						<tr>
							<td>Tháng <?php echo $m; ?></td>
							<td><?php echo $data_nhap[$m-1]; ?> phiếu</td>
							<td><?php echo $data_xuat[$m-1]; ?> phiếu</td>
						</tr>
						<?php endfor; ?>
					</table>
				</div>
			</div>
			<!-- Vùng biểu đồ cột phiếu xuất nhập -->
			<div class="title">
				<h2>Biểu đồ Phiếu Xuất/Nhập theo tháng</h2>
			</div>
			<div class="main-mini-content">
				<script type="text/javascript">
					// Biểu đồ cột thống kê phiếu xuất nhập theo tháng
					google.charts.load('current', { 'packages': ['corechart'] });
					google.charts.setOnLoadCallback(drawChartPhieu);

					function drawChartPhieu() {
						var data = new google.visualization.DataTable();
						data.addColumn('string', 'Tháng');
						data.addColumn('number', 'Phiếu nhập');
						data.addColumn('number', 'Phiếu xuất');
						data.addRows([
							<?php
							for ($m = 1; $m <= 12; $m++) {
								echo "['$m', {$data_nhap[$m-1]}, {$data_xuat[$m-1]}],";
							}
							?>
						]);
						var options = {
							title: 'Phiếu Xuất/Nhập theo tháng năm <?php echo $year; ?>',
							legend: { position: 'top' },
							hAxis: { title: 'Tháng', format: '0' },
							vAxis: { title: 'Số phiếu' },
							width: '100%',
							height: 350,
							isStacked: false,
							colors: ['#388e3c', '#d32f2f']
						};
						var chart = new google.visualization.ColumnChart(document.getElementById('chart_phieu'));
						chart.draw(data, options);
					}
				</script>
				<div id="chart_phieu"></div>
			</div>
		</div>
	</div>
</div>
</content>
</body>

</html>
<script>
document.addEventListener('DOMContentLoaded', function() {
	const btn = document.getElementById('toggle-table-phieu');
	const table = document.getElementById('table-phieu-xuat-nhap');
	const arrow = document.getElementById('arrow-icon');
	let isOpen = true;
	btn.addEventListener('click', function() {
		isOpen = !isOpen;
		table.style.display = isOpen ? 'block' : 'none';
		arrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
	});
});
</script>