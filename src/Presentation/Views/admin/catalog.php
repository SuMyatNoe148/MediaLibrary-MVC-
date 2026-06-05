<?php require BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>

<div class="section page">
    <div class="wrapper">

        <div class="admin-header">
            <div class="header-left">
                <?= IconHelper::library('auth-icon') ?>
                <h1>Manage Catalog</h1>
            </div>
            <a href="index.php?page=admin" class="btn-back">&larr; Back to Dashboard</a>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Toolbar -->
        <div class="toolbar">
            <div class="filter-section">
                <form method="get" class="filter-form">
                    <input type="hidden" name="page" value="admin-catalog">
                    <label for="category">Filter by Category:</label>
                    <select name="category" id="category" onchange="this.form.submit()">
                        <option value="all" <?= $category === 'all' ? 'selected' : '' ?>>All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
            <button class="btn-primary" onclick="openModal('addModal')">
                <span>+</span> Add New Media
            </button>
        </div>

        <!-- Stats -->
        <div class="stats-bar">
            <span>Showing <?= count($catalog) ?> of <?= $totalItems ?> items</span>
            <?php if ($category !== 'all'): ?>
                <span class="filter-tag">Category: <?= htmlspecialchars($category) ?></span>
            <?php endif; ?>
        </div>

        <!-- Media Grid -->
        <div class="admin-section">
            <?php if (empty($catalog)): ?>
                <div class="empty-state">
                    <p>No media items found.</p>
                </div>
            <?php else: ?>
                <div class="media-grid">
                    <?php foreach ($catalog as $item): ?>
                        <div class="media-card">
                            <div class="media-image">
                                <img src="<?= htmlspecialchars($item['img']) ?>" 
                                     alt="<?= htmlspecialchars($item['title']) ?>"
                                     onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22180%22 height=%22180%22><rect width=%22180%22 height=%22180%22 fill=%22%23f3f4f6%22/><text x=%2290%22 y=%2290%22 text-anchor=%22middle%22 fill=%229ca3af%22 font-size=%2214%22>No Image</text></svg>'">
                                <div class="media-overlay">
                                    <a href="index.php?page=details&id=<?= $item['media_id'] ?>" class="btn-view" target="_blank">View</a>
                                </div>
                            </div>
                            <div class="media-info">
                                <h3><?= htmlspecialchars($item['title']) ?></h3>
                                <div class="meta">
                                    <span class="badge category-<?= strtolower($item['category']) ?>"><?= htmlspecialchars($item['category']) ?></span>
                                    <span class="badge"><?= htmlspecialchars($item['genre']) ?></span>
                                </div>
                                <div class="details">
                                    <span><?= htmlspecialchars($item['year']) ?></span>
                                    <span><?= htmlspecialchars($item['format']) ?></span>
                                </div>
                                <div class="stats">
                                    <span>⭐ <?= $item['avg_rating'] ? number_format($item['avg_rating'], 1) : 'N/A' ?></span>
                                </div>
                            </div>
                            <div class="media-actions">
                                <a href="index.php?page=admin-catalog&category=<?= urlencode($category) ?>&p=<?= $page ?>&edit=<?= $item['media_id'] ?>" class="btn-edit">
                                    Edit
                                </a>
                                <form method="post" style="display: inline;" id="deleteForm<?= $item['media_id'] ?>">
                                    <input type="hidden" name="media_id" value="<?= $item['media_id'] ?>">
                                    <input type="hidden" name="action" value="delete_media">
                                    <button type="button" class="btn-delete" onclick="confirmDelete('<?= htmlspecialchars(addslashes($item['title'])) ?>', <?= $item['media_id'] ?>)">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination-container">
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="index.php?page=admin-catalog&category=<?= urlencode($category) ?>&p=<?= $page - 1 ?>" class="page-link">&larr; Prev</a>
                            <?php else: ?>
                                <span class="page-link disabled">&larr; Prev</span>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <?php if ($i == $page): ?>
                                    <span class="page-link active"><?= $i ?></span>
                                <?php else: ?>
                                    <a href="index.php?page=admin-catalog&category=<?= urlencode($category) ?>&p=<?= $i ?>" class="page-link"><?= $i ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <a href="index.php?page=admin-catalog&category=<?= urlencode($category) ?>&p=<?= $page + 1 ?>" class="page-link">Next &rarr;</a>
                            <?php else: ?>
                                <span class="page-link disabled">Next &rarr;</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- Add Media Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Media</h2>
            <span class="close" onclick="closeModal('addModal')">&times;</span>
        </div>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="create_media">
            <div class="form-group">
                <label>Title *</label>
                <input type="text" name="title" required placeholder="Enter title">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Category *</label>
                    <select name="media_types_id" id="categorySelect" required onchange="updateUploadFolder()">
                        <option value="1">Books</option>
                        <option value="2">Movies</option>
                        <option value="3">Music</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Genre *</label>
                    <select name="genre_id" required>
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?= $genre['genre_id'] ?>"><?= htmlspecialchars($genre['genre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Year *</label>
                    <input type="number" name="year" required value="<?= date('Y') ?>" min="1900" max="<?= date('Y') + 1 ?>">
                </div>
                <div class="form-group">
                    <label>Format *</label>
                    <input type="text" name="format" required placeholder="e.g., DVD, CD, Paperback">
                </div>
            </div>
            <div class="form-group">
                <label>Upload Image *</label>
                <input type="file" name="image_file" id="imageFile" accept="image/jpeg,image/png,image/gif,image/webp,.jpeg,.jpg,.png,.gif,.webp" required onchange="previewImage(this)">
                <small>Max 5MB. JPG, JPEG, PNG, GIF, WebP allowed. Will be saved to: <span id="uploadPath">Public/img/books/</span></small>
                <div id="imagePreview" class="image-preview" style="display: none;">
                    <img id="previewImg" src="" alt="Preview" style="max-width: 200px; max-height: 200px; margin-top: 10px; border-radius: 8px;">
                </div>
            </div>
            <div class="form-group">
                <label>People (Author, Director, Star, Artist)</label>
                <div id="mediaPeopleContainer">
                    <div class="media-person-row">
                        <select name="people_id[]" class="person-select">
                            <option value="">Select Person</option>
                            <?php foreach ($people as $person): ?>
                                <option value="<?= $person['people_id'] ?>"><?= htmlspecialchars($person['fullname']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="role_id[]" class="role-select">
                            <option value="">Select Role</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['role_id'] ?>"><?= htmlspecialchars($role['role']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" class="btn-small" onclick="removePersonRow(this)" style="display: none;">Remove</button>
                    </div>
                </div>
                <div id="newPersonSection" style="display: none; margin-top: 10px; padding: 15px; background: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb;">
                    <h4 style="margin: 0 0 10px 0; font-size: 14px; color: #374151;">Add New Person</h4>
                    <div class="form-row" style="margin-bottom: 0;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <input type="text" name="new_person_name" id="newPersonName" placeholder="Enter person name" style="width: 100%;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <select name="new_person_role" id="newPersonRole" style="width: 100%;">
                                <option value="">Select Role</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['role_id'] ?>"><?= htmlspecialchars($role['role']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 10px;">
                    <button type="button" class="btn-secondary" onclick="toggleNewPersonSection()">+ Add New Person</button>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeModal('addModal')">Cancel</button>
                <button type="submit" class="btn-primary">Create Media</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Media Modal -->
<?php if ($editMedia): ?>
<div id="editModal" class="modal" style="display: block;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Media</h2>
            <a href="index.php?page=admin-catalog&category=<?= urlencode($category) ?>&p=<?= $page ?>" class="close">&times;</a>
        </div>
        <form method="post">
            <input type="hidden" name="action" value="update_media">
            <input type="hidden" name="media_id" value="<?= $editMedia['media_id'] ?>">
            <div class="form-group">
                <label>Title *</label>
                <input type="text" name="title" required value="<?= htmlspecialchars($editMedia['title']) ?>">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Category *</label>
                    <select name="media_types_id" required>
                        <option value="1" <?= $editMedia['media_types_id'] == 1 ? 'selected' : '' ?>>Books</option>
                        <option value="2" <?= $editMedia['media_types_id'] == 2 ? 'selected' : '' ?>>Movies</option>
                        <option value="3" <?= $editMedia['media_types_id'] == 3 ? 'selected' : '' ?>>Music</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Genre *</label>
                    <select name="genre_id" required>
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?= $genre['genre_id'] ?>" <?= $editMedia['genre_id'] == $genre['genre_id'] ? 'selected' : '' ?>><?= htmlspecialchars($genre['genre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Year *</label>
                    <input type="number" name="year" required value="<?= $editMedia['year'] ?>" min="1900" max="<?= date('Y') + 1 ?>">
                </div>
                <div class="form-group">
                    <label>Format *</label>
                    <input type="text" name="format" required value="<?= htmlspecialchars($editMedia['format']) ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Image Path *</label>
                <input type="text" name="img" required value="<?= htmlspecialchars($editMedia['img']) ?>">
                <small>Current: <?= htmlspecialchars($editMedia['img']) ?></small>
            </div>
            <div class="form-group">
                <label>People (Author, Director, Star, Artist)</label>
                <div id="editMediaPeopleContainer">
                    <div class="media-person-row">
                        <select name="people_id[]" class="person-select">
                            <option value="">Select Person</option>
                            <?php foreach ($people as $person): ?>
                                <option value="<?= $person['people_id'] ?>"><?= htmlspecialchars($person['fullname']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="role_id[]" class="role-select">
                            <option value="">Select Role</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['role_id'] ?>"><?= htmlspecialchars($role['role']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" class="btn-small" onclick="removePersonRow(this)" style="display: none;">Remove</button>
                    </div>
                </div>
                <div id="editNewPersonSection" style="display: none; margin-top: 10px; padding: 15px; background: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb;">
                    <h4 style="margin: 0 0 10px 0; font-size: 14px; color: #374151;">Add New Person</h4>
                    <div class="form-row" style="margin-bottom: 0;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <input type="text" name="edit_new_person_name" id="editNewPersonName" placeholder="Enter person name" style="width: 100%;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <select name="edit_new_person_role" id="editNewPersonRole" style="width: 100%;">
                                <option value="">Select Role</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['role_id'] ?>"><?= htmlspecialchars($role['role']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 10px;">
                    <button type="button" class="btn-secondary" onclick="toggleNewPersonSectionEdit()">+ Add New Person</button>
                </div>
            </div>
            <div class="form-actions">
                <a href="index.php?page=admin-catalog&category=<?= urlencode($category) ?>&p=<?= $page ?>" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
function confirmDelete(title, formId) {
    if (confirm('Are you sure you want to delete "' + title + '"? This action cannot be undone.')) {
        document.getElementById('deleteForm' + formId).submit();
    }
}

function addPersonRow() {
    const container = document.getElementById('mediaPeopleContainer');
    const newRow = document.createElement('div');
    newRow.className = 'media-person-row';
    newRow.innerHTML = `
        <select name="people_id[]" class="person-select">
            <option value="">Select Person</option>
            <?php foreach ($people as $person): ?>
                <option value="<?= $person['people_id'] ?>"><?= htmlspecialchars($person['fullname']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="role_id[]" class="role-select">
            <option value="">Select Role</option>
            <?php foreach ($roles as $role): ?>
                <option value="<?= $role['role_id'] ?>"><?= htmlspecialchars($role['role']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="button" class="btn-small btn-danger" onclick="removePersonRow(this)">Remove</button>
    `;
    container.appendChild(newRow);
}

function toggleNewPersonSection() {
    const section = document.getElementById('newPersonSection');
    section.style.display = section.style.display === 'none' ? 'block' : 'none';
}

function addPersonRowEdit() {
    const container = document.getElementById('editMediaPeopleContainer');
    const newRow = document.createElement('div');
    newRow.className = 'media-person-row';
    newRow.innerHTML = `
        <select name="people_id[]" class="person-select">
            <option value="">Select Person</option>
            <?php foreach ($people as $person): ?>
                <option value="<?= $person['people_id'] ?>"><?= htmlspecialchars($person['fullname']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="role_id[]" class="role-select">
            <option value="">Select Role</option>
            <?php foreach ($roles as $role): ?>
                <option value="<?= $role['role_id'] ?>"><?= htmlspecialchars($role['role']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="button" class="btn-small btn-danger" onclick="removePersonRow(this)">Remove</button>
    `;
    container.appendChild(newRow);
}

function toggleNewPersonSectionEdit() {
    const section = document.getElementById('editNewPersonSection');
    section.style.display = section.style.display === 'none' ? 'block' : 'none';
}

function removePersonRow(button) {
    const row = button.parentElement;
    const container = document.getElementById('mediaPeopleContainer');
    if (container.children.length > 1) {
        row.remove();
    }
}

function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}

// Update upload folder path display
function updateUploadFolder() {
    const categorySelect = document.getElementById('categorySelect');
    const uploadPath = document.getElementById('uploadPath');
    const folderMap = {
        '1': 'books',
        '2': 'movies',
        '3': 'music'
    };
    const folder = folderMap[categorySelect.value] || 'other';
    uploadPath.textContent = 'Public/img/' + folder + '/';
}

// Preview uploaded image
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
