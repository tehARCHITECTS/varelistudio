<?php
require_once 'config/database.php';

// Fetch projects from database
$conn = getDBConnection();
$sql = "SELECT * FROM projects ORDER BY created_at DESC";
$result = $conn->query($sql);
$projects = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);
    
    if ($stmt->execute()) {
        $success_message = "Thank you! Your message has been sent successfully.";
    } else {
        $error_message = "Sorry, there was an error. Please try again.";
    }
    $stmt->close();
}

$conn->close();

include 'includes/header.php';
?>

<!-- Hero Section -->
<section id="home" class="hero-section">
<img src="assets/images/main.jpg">
<!-- 
</section>
<section id="home" class="hero-section">
     -->
    <div class="animated-bg"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title fade-in"></h1>
            <h2 class="hero-subtitle fade-in-delay-1">Architecture Consultant | Design & Build</h2>
            <p class="hero-description fade-in-delay-2">
                <span id="typed-text"></span><span class="cursor">|</span>
            </p>
            <div class="hero-buttons fade-in-delay-3">
                <a href="#projects" class="btn btn-primary btn-glow">View Projects</a>
                <a href="#contact" class="btn btn-outline">Contact Me</a>
            </div>
        </div>
    </div>
 </section>

<!-- About Section -->
<section id="about" class="about-section section-padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="about-image reveal-left">
                    <img src="assets/images/about.jpg" alt="About Varelis Architects" class="img-fluid rounded-3">
                </div>
            </div>
            <div class="col-lg-7">
                <div class="about-content reveal-right">
                    <h2 class="section-title">Tentang Kami</h2>
                    <p class="lead">Menciptakan Keunggulan Arsitektur</p>
                    <p>Varelis Architects adalah konsultan desain arsitektur terkemuka yang mengkhususkan diri pada solusi desain inovatif dan berkelanjutan. Dengan pengalaman bertahun-tahun dalam proyek-proyek residensial, komersial, dan perhotelan, kami mewujudkan visi menjadi kenyataan.</p>
                    <p>Pendekatan kami menggabungkan kreativitas dengan fungsionalitas, memastikan setiap proyek mencerminkan komitmen kami terhadap keunggulan dan perhatian terhadap detail.</p>
                    <div class="core-values mt-4">
                        <div class="value-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Inovasi & Kreativitas</span>
                        </div>
                        <div class="value-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Kualitas & Keunggulan</span>
                        </div>
                        <div class="value-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Kepuasan Pelanggan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Skills Section -->
<section id="skills" class="skills-section section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title reveal-up">PAKET HARGA</h2>
            <p class="section-subtitle reveal-up">Konsultasikan pada kami</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="skill-card reveal-up">
                    <div class="skill-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h4>PAKET BASIC</h4>
                    <p>Gambar Denah</p>
                    <p>Gambar 3D Exterior (Depan, Samping & Belakang)</p>
                    <div class="progress-bar">
                        <div class="progress-fill" data-progress="95"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="skill-card reveal-up delay-1">
                    <div class="skill-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h4>PAKET STANDAR</h4>
                    <p>Gambar Denah</p>
                    <p>Gambar 3D Exterior (Depan, Samping & Belakang)</p>
                    <p>Gambar Kerja & Detail (Arsitektur, Struktur & MEP)</p>
                    <div class="progress-bar">
                        <div class="progress-fill" data-progress="90"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="skill-card reveal-up delay-2">
                    <div class="skill-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h4>PAKET PREMIUM</h4>
                    <p>Gambar Denah</p>
                    <p>Gambar 3D Exterior (Depan, Samping & Belakang)</p>
                    <p>Gambar Kerja & Detail (Arsitektur, Struktur & MEP)</p>
                    <p>Gambar 3D Interior (Semua Ruangan)</p>
                    <p>Rencana Anggaran Biaya (RAB) Arsitektur, Struktur & MEP</p>
                    <div class="progress-bar">
                        <div class="progress-fill" data-progress="92"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="skill-card reveal-up delay-3">
                    <div class="skill-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h4>PAKET PLATINUM</h4>
                    <p>Gambar Denah</p>
                    <p>Gambar 3D Exterior (Depan, Samping & Belakang)</p>
                    <p>Animasi 3D Exterior</p>
                    <p>Gambar Kerja & Detail (Arsitektur, Struktur & MEP)</p>
                    <p>Rencana Anggaran Biaya (RAB) Arsitektur, Struktur & MEP</p>
                    <p>Gambar 3D Interior (Semua Ruangan)</p>
                    <p>Animasi 3D Interior</p>
                    <p>Gambar Kerja dan Detail Interior</p>
                    <p>Rencana Anggaran Biaya (RAB) Interior</p>
                    <div class="progress-bar">
                        <div class="progress-fill" data-progress="88"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Projects Section -->
<section id="projects" class="projects-section section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title reveal-up">PORTOFOLIO</h2>
            <p class="section-subtitle reveal-up"></p>
        </div>
        
        <div class="filter-buttons text-center mb-4 reveal-up">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="residential">Residential</button>
            <button class="filter-btn" data-filter="commercial">Commercial</button>
            <button class="filter-btn" data-filter="interior">Interior</button>
        </div>

        <div class="row g-4" id="projectsGrid">
            <?php if (count($projects) > 0): ?>
                <?php foreach ($projects as $project): ?>
                    <div class="col-md-6 col-lg-4 project-item" data-category="<?php echo htmlspecialchars($project['category']); ?>">
                        <div class="project-card reveal-up">
                            <div class="project-image">
                                <img src="<?php echo htmlspecialchars($project['image']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                                <div class="project-overlay">
                                    <button class="btn btn-light btn-sm" onclick="showProjectModal(<?php echo $project['id']; ?>)">
                                        <i class="fas fa-eye"></i> View Details
                                    </button>
                                </div>
                            </div>
                            <div class="project-content">
                                <h4><?php echo htmlspecialchars($project['title']); ?></h4>
                                <p><?php echo htmlspecialchars(substr($project['description'], 0, 100)); ?>...</p>
                                <div class="project-tech">
                                    <?php 
                                    $techs = explode(',', $project['tech_stack']);
                                    foreach ($techs as $tech): 
                                    ?>
                                        <span class="tech-badge"><?php echo htmlspecialchars(trim($tech)); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No projects available yet. Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="services-section section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title reveal-up">LAYANAN</h2>
            <p class="section-subtitle reveal-up"></p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="service-card reveal-up">
                    <div class="service-icon">
                        <i class="fas fa-pencil-ruler"></i>
                    </div>
                    <h4>Architectural Design</h4>
                    <p>Complete architectural design services from concept development to detailed construction drawings.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="service-card reveal-up delay-1">
                    <div class="service-icon">
                        <i class="fas fa-drafting-compass"></i>
                    </div>
                    <h4>3D Visualization</h4>
                    <p>Photorealistic 3D renderings and virtual tours to bring your project to life before construction.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="service-card reveal-up delay-2">
                    <div class="service-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h4>Project Management</h4>
                    <p>End-to-end project management ensuring timely delivery and quality control throughout construction.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="service-card reveal-up delay-3">
                    <div class="service-icon">
                        <i class="fas fa-paint-roller"></i>
                    </div>
                    <h4>Interior Design</h4>
                    <p>Sophisticated interior design solutions that create beautiful and functional living spaces.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="service-card reveal-up delay-4">
                    <div class="service-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h4>Construction Services</h4>
                    <p>Professional construction and contracting services with experienced teams and quality materials.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="service-card reveal-up delay-5">
                    <div class="service-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h4>Consultation</h4>
                    <p>Expert consultation services to help you make informed decisions about your project.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="contact-section section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title reveal-up">HUBUNGI KAMI</h2>
            <p class="section-subtitle reveal-up"></p>
        </div>
        <div class="row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="contact-info reveal-left">
                    <h4>Contact Information</h4>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h5>Address</h5>
                            <p>Pangandaran - Jawa Barat</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h5>Phone</h5>
                            <p>+62 811 212 805</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h5>Email</h5>
                            <p>info@varelisarchitects.com</p>
                        </div>
                    </div>
                    <a href="https://wa.me/+62811212805" class="btn btn-success btn-whatsapp mt-4" target="_blank">
                        <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="contact-form reveal-right">
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="#contact">
                        <div class="mb-3">
                            <input type="text" class="form-control" name="name" placeholder="Your Name" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" name="message" rows="5" placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" name="contact_submit" class="btn btn-primary btn-glow w-100">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Project Modal -->
<div class="modal fade" id="projectModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Content loaded via JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
// Project data for modal
const projectsData = <?php echo json_encode($projects); ?>;

function showProjectModal(projectId) {
    const project = projectsData.find(p => p.id == projectId);
    if (project) {
        document.getElementById('modalTitle').textContent = project.title;
        document.getElementById('modalBody').innerHTML = `
            <img src="${project.image}" class="img-fluid rounded mb-3" alt="${project.title}">
            <p>${project.description}</p>
            <p><strong>Technologies:</strong> ${project.tech_stack}</p>
            ${project.demo_link ? `<a href="${project.demo_link}" class="btn btn-primary" target="_blank">View Live Demo</a>` : ''}
        `;
        new bootstrap.Modal(document.getElementById('projectModal')).show();
    }
}
</script>

<?php include 'includes/footer.php'; ?>
