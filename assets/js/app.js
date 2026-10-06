body {
    background: #f6f7fb;
    font-family: Arial, sans-serif;
}

.hero {
    background: linear-gradient(135deg, #0d6efd, #111827);
    color: white;
    padding: 80px 0;
}

.card-product {
    border: 1px solid #e8e8e8;
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    transition: transform .2s ease, box-shadow .2s ease;
}

.card-product:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 24px rgba(17, 24, 39, 0.08);
}

.card-product img {
    width: 100%;
    height: 220px;
    object-fit: cover;
}

.price-tag {
    color: #198754;
    font-weight: 700;
    font-size: 1.2rem;
}

.form-box,
.sidebar-card {
    background: #ffffff;
    border: 1px solid #ececec;
    border-radius: 18px;
    padding: 24px;
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
}

.admin-nav a {
    display: block;
    padding: 10px 12px;
    margin-bottom: 8px;
    border-radius: 8px;
    color: #eef2ff;
    text-decoration: none;
}

.admin-nav a:hover,
.admin-nav a.active {
    background: #0d6efd;
    color: white;
}

@media (max-width: 768px) {
    .hero {
        padding: 50px 0;
    }
}
