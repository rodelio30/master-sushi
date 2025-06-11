<?php
include 'header.php';
?>  
<body>
<?php
$nav_active = "products";
include 'nav.php';
?>  

<style>
#productSection { min-height: 85vh;
/* background-color: #fff; */
background: url('assets/img/bg-cta.png') no-repeat center center/cover;
border-radius: 12px;
box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
padding: 20px;
box-sizing: border-box;
}
</style>
    
<!-- Call to Action Section -->

<section id="productSection">
<?php include 'product_list.php';?>
</section>

<?php include 'footer.php';?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
</html>