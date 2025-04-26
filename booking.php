<?php include 'includes/header.php'; ?>

<div class="page-header parallax" style="background-image: url('assets/images/booking-header.jpg');">
    <div class="container">
        <h1 class="animate__animated animate__fadeInUp">Book Your Event Decoration</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Event Booking</li>
            </ol>
        </nav>
    </div>
</div>

<section class="booking-section">
    <div class="container">
        <div class="booking-container">
            <div class="booking-form animate__animated animate__fadeInLeft">
                <h2>Create Your Perfect Event</h2>
                <p>Fill out the form below and our team will get back to you within 24 hours.</p>
                
                <form id="booking-form" action="process-booking.php" method="POST" class="needs-validation">
                    <div class="form-group">
                        <label for="event-type">Event Type</label>
                        <select id="event-type" name="event_type" class="form-control" required>
                            <option value="">Select Event Type</option>
                            <option value="wedding">Wedding</option>
                            <option value="birthday">Birthday</option>
                            <option value="anniversary">Anniversary</option>
                            <option value="corporate">Corporate Event</option>
                            <option value="religious">Religious Ceremony</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="event-date">Event Date</label>
                            <input type="date" id="event-date" name="event_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="event-time">Event Time</label>
                            <input type="time" id="event-time" name="event_time" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Event Location</label>
                        <textarea id="location" name="location" class="form-control" rows="2" required placeholder="Full address of the event venue"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Decoration Type</label>
                        <div class="decoration-options">
                            <div class="option-card">
                                <input type="radio" id="floral-arches" name="decoration_type" value="floral_arches" required>
                                <label for="floral-arches">
                                    <img src="assets/images/floral-arch.jpg" alt="Floral Arches">
                                    <h4>Floral Arches</h4>
                                    <p>Beautiful floral arches for entrances and stages</p>
                                </label>
                            </div>
                            <div class="option-card">
                                <input type="radio" id="table-centerpieces" name="decoration_type" value="table_centerpieces">
                                <label for="table-centerpieces">
                                    <img src="assets/images/centerpiece.jpg" alt="Table Centerpieces">
                                    <h4>Table Centerpieces</h4>
                                    <p>Elegant floral arrangements for tables</p>
                                </label>
                            </div>
                            <div class="option-card">
                                <input type="radio" id="full-venue" name="decoration_type" value="full_venue">
                                <label for="full-venue">
                                    <img src="assets/images/full-venue.jpg" alt="Full Venue Decoration">
                                    <h4>Full Venue Decoration</h4>
                                    <p>Complete venue transformation with flowers</p>
                                </label>
                            </div>
                            <div class="option-card">
                                <input type="radio" id="custom" name="decoration_type" value="custom">
                                <label for="custom">
                                    <img src="assets/images/custom.jpg" alt="Custom Design">
                                    <h4>Custom Design</h4>
                                    <p>Tell us your vision and we'll create it</p>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Choose Your Decoration Package</label>
                        <div class="decoration-options">
                            <div class="option-card">
                                <input type="radio" name="package" id="basic" value="basic" required>
                                <label for="basic">
                                    <div class="package-header">
                                        <h4>Basic Package</h4>
                                        <div class="price">$499</div>
                                    </div>
                                    <ul class="package-features">
                                        <li><i class="fas fa-check"></i> Entry Decoration</li>
                                        <li><i class="fas fa-check"></i> 2 Flower Stands</li>
                                        <li><i class="fas fa-check"></i> Basic Table Centerpieces</li>
                                    </ul>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="budget">Estimated Budget ($)</label>
                        <input type="range" id="budget" name="budget" min="100" max="10000" step="100" value="1000" class="form-control">
                        <div class="budget-value">$<span id="budget-value">1000</span></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Additional Notes/Special Requests</label>
                        <textarea id="notes" name="additional_notes" class="form-control" rows="4" placeholder="Tell us about your event theme, color preferences, or any special requirements"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Submit Booking Request
                        </button>
                    </div>
                </form>
            </div>

            <aside class="booking-info animate__animated animate__fadeInRight">
                <div class="info-card">
                    <h3>Why Choose Our Decoration Services?</h3>
                    <ul>
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <h4>Professional Team</h4>
                                <p>Experienced decorators with an eye for detail</p>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <h4>Fresh Flowers</h4>
                                <p>We use only the freshest, highest quality flowers</p>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <h4>Custom Designs</h4>
                                <p>Tailored to your event theme and preferences</p>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <h4>On-Time Setup</h4>
                                <p>We'll have your venue ready before your guests arrive</p>
                            </div>
                        </li>
                    </ul>
                </div>
                
                <div class="testimonial-card" data-aos="fade-left">
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p>"Flora transformed our wedding venue into a floral paradise. Their attention to detail was incredible and they perfectly captured our vision."</p>
                    <div class="author">
                        <img src="assets/images/booking-testimonial.jpg" alt="Jessica & Michael">
                        <div>
                            <h4>Jessica & Michael</h4>
                            <span>Wedding Clients</span>
                        </div>
                    </div>
                </div>
                
                <div class="need-help">
                    <h3>Need Help?</h3>
                    <p>Contact our event specialists</p>
                    <a href="tel:+1234567890" class="btn btn-outline btn-block">
                        <i class="fas fa-phone"></i> Call Us
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>