<?php
include 'header.php';
?>  
<style>
#contactSection {
  min-height: 85vh;
  background: url('assets/img/bg-cta.png') no-repeat center center/cover;
  padding: 40px 20px;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}
/* .contact-content {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  color: #333;
} */
 .contact-content {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
  gap: 20px;
  color: #333;
  max-width: 1200px;
  width: 100%;
}

.contact-info {
  flex: 1;
  min-width: 300px;
  background-color: #ffffff;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.contact-info h2 {
  font-size: 28px;
  margin-bottom: 15px;
  color: #333;
}

.contact-info ul {
  list-style: none;
  padding: 0;
}

.contact-info ul li {
  font-size: 16px;
  margin-bottom: 10px;
}

.contact-info ul li a {
  color: black;
}

.contact-map {
  flex: 1.5;
  min-width: 300px;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
  .contact-content {
    flex-direction: column;
  }
}

</style>
<body>

<?php
$nav_active = "contact";
include 'nav.php';
?>  
    
<!-- Call to Action Section -->
 <section id="contactSection">
  <div class="container">
    <div class="contact-content">
      <div class="contact-info">
                <div class="text-center">
        <img src="../assets/img/contact.gif" alt="Contact GIF"  height="50">
        </div>
        <h2>Contact Us</h2>
        <p>Feel free to reach out to us through the following:</p>
        <ul>
          <li><strong>Phone:</strong> 09613165793</li>
          <li><strong>Email:</strong> <a href= "mailto:joeybago.jb@gmail.com">joeybago.jb@gmail.com</a></li>
          <li><strong>Address:</strong> 71a Bonny Serrano Avenue , Quezon City, Philippines</li>
          <li><strong>Facebook Page:</strong> 
            <a href="https://www.facebook.com/mastersushi0713/" target="_blank">Master SUSHI</a>
          </li>
          <li> <strong>Time Open:</strong> 10:00 AM – 7:00 PM (Monday to Sunday) </li>
        </ul>
      </div>
      <div class="contact-map">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4087.0329984568725!2d121.04963109999998!3d14.609699900000008!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b7a40f73ed77%3A0xc923384d7ef4debb!2sMaster%20Sushi!5e1!3m2!1sen!2sph!4v1747619550399!5m2!1sen!2sph" 
          width="100%" 
          height="500" 
          style="border:0;" 
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="no-referrer-when-downgrade">

        </iframe>
        <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4087.0329984568725!2d121.04963109999998!3d14.609699900000008!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b7a40f73ed77%3A0xc923384d7ef4debb!2sMaster%20Sushi!5e1!3m2!1sen!2sph!4v1747619550399!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> -->
      </div>
    </div>
  </div>
</section>
<?php include 'footer.php';?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</html>
