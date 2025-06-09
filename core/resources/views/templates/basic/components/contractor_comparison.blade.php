<!-- Contractor Comparison Tool -->
<div class="comparison-tool" id="comparisonTool" style="display: none;">
    <div class="comparison-header">
        <h5>🔍 So Sánh Thợ</h5>
        <button class="close-comparison" onclick="closeComparison()">
            <i class="las la-times"></i>
        </button>
    </div>
    
    <div class="comparison-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Tiêu chí</th>
                    <th id="contractor1Header"></th>
                    <th id="contractor2Header"></th>
                    <th id="contractor3Header"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>📊 Đánh giá</td>
                    <td id="rating1"></td>
                    <td id="rating2"></td>
                    <td id="rating3"></td>
                </tr>
                <tr>
                    <td>💰 Mức giá</td>
                    <td id="price1"></td>
                    <td id="price2"></td>
                    <td id="price3"></td>
                </tr>
                <tr>
                    <td>⏰ Thời gian phản hồi</td>
                    <td id="response1"></td>
                    <td id="response2"></td>
                    <td id="response3"></td>
                </tr>
                <tr>
                    <td>🏆 Kinh nghiệm</td>
                    <td id="experience1"></td>
                    <td id="experience2"></td>
                    <td id="experience3"></td>
                </tr>
                <tr>
                    <td>📍 Khoảng cách</td>
                    <td id="distance1"></td>
                    <td id="distance2"></td>
                    <td id="distance3"></td>
                </tr>
                <tr>
                    <td>✅ Tình trạng</td>
                    <td id="status1"></td>
                    <td id="status2"></td>
                    <td id="status3"></td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="comparison-actions">
        <button class="btn btn-outline-primary" onclick="exportComparison()">
            <i class="las la-download"></i>
            Tải về PDF
        </button>
        <button class="btn btn-primary" onclick="proceedWithSelection()">
            <i class="las la-check"></i>
            Chọn & Thuê
        </button>
    </div>
</div>

<!-- Quick Decision Support -->
<div class="decision-support-panel">
    <h6>🤔 Cần hỗ trợ quyết định?</h6>
    <div class="support-options">
        <button class="support-option" onclick="showComparisonTool()">
            <i class="las la-balance-scale"></i>
            <span>So sánh thợ</span>
        </button>
        <button class="support-option" onclick="getRecommendation()">
            <i class="las la-magic"></i>
            <span>AI gợi ý</span>
        </button>
        <button class="support-option" onclick="consultExpert()">
            <i class="las la-phone"></i>
            <span>Tư vấn miễn phí</span>
        </button>
    </div>
</div>

<!-- Smart Recommendation Engine -->
<div class="recommendation-engine" id="recommendationEngine" style="display: none;">
    <div class="recommendation-header">
        <h5>🎯 AI Gợi Ý Thợ Phù Hợp</h5>
        <p class="text-muted">Dựa trên preferences và lịch sử tìm kiếm</p>
    </div>
    
    <div class="recommendation-questions">
        <div class="question-group">
            <label>💰 Ngân sách của bạn?</label>
            <div class="budget-options">
                <button class="budget-option" data-budget="low">< 500k</button>
                <button class="budget-option" data-budget="mid">500k - 2M</button>
                <button class="budget-option" data-budget="high">> 2M</button>
            </div>
        </div>
        
        <div class="question-group">
            <label>⏰ Mức độ khẩn cấp?</label>
            <div class="urgency-options">
                <button class="urgency-option" data-urgency="low">Không gấp (1-2 tuần)</button>
                <button class="urgency-option" data-urgency="mid">Bình thường (3-7 ngày)</button>
                <button class="urgency-option" data-urgency="high">Gấp (1-2 ngày)</button>
            </div>
        </div>
        
        <div class="question-group">
            <label>🎯 Ưu tiên nào quan trọng nhất?</label>
            <div class="priority-options">
                <button class="priority-option" data-priority="price">Giá cả</button>
                <button class="priority-option" data-priority="quality">Chất lượng</button>
                <button class="priority-option" data-priority="speed">Tốc độ</button>
                <button class="priority-option" data-priority="experience">Kinh nghiệm</button>
            </div>
        </div>
        
        <button class="btn btn-primary w-100 mt-3" onclick="generateRecommendation()">
            <i class="las la-magic"></i>
            Tạo Gợi Ý
        </button>
    </div>
    
    <div class="recommendation-results" id="recommendationResults" style="display: none;">
        <h6>✨ Thợ được gợi ý cho bạn:</h6>
        <div id="recommendedContractors"></div>
    </div>
</div>

<style>
/* Comparison Tool Styles */
.comparison-tool {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    z-index: 1000;
    width: 90%;
    max-width: 800px;
    max-height: 80vh;
    overflow-y: auto;
}

.comparison-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.comparison-table {
    padding: 1.5rem;
}

.comparison-table table {
    width: 100%;
    margin-bottom: 0;
}

.comparison-table th {
    background: #f7fafc;
    padding: 12px;
    font-weight: 600;
    border: none;
}

.comparison-table td {
    padding: 12px;
    border-top: 1px solid #e2e8f0;
    vertical-align: middle;
}

.comparison-actions {
    padding: 1.5rem;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

/* Decision Support Panel */
.decision-support-panel {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 1.5rem;
    border-radius: 12px;
    margin: 2rem 0;
    text-align: center;
}

.support-options {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 1rem;
    flex-wrap: wrap;
}

.support-option {
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    padding: 12px 16px;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    transition: all 0.3s ease;
    min-width: 120px;
}

.support-option:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-2px);
}

.support-option i {
    font-size: 1.5rem;
}

.support-option span {
    font-size: 0.875rem;
    font-weight: 500;
}

/* Recommendation Engine */
.recommendation-engine {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    margin: 2rem 0;
}

.question-group {
    margin-bottom: 2rem;
}

.question-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #2d3748;
}

.budget-options, .urgency-options, .priority-options {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.budget-option, .urgency-option, .priority-option {
    background: #f7fafc;
    border: 2px solid #e2e8f0;
    color: #4a5568;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.budget-option:hover, .urgency-option:hover, .priority-option:hover {
    border-color: #667eea;
    background: #f0f4ff;
}

.budget-option.active, .urgency-option.active, .priority-option.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.recommendation-results {
    border-top: 1px solid #e2e8f0;
    padding-top: 2rem;
    margin-top: 2rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .comparison-tool {
        width: 95%;
        height: 90vh;
    }
    
    .support-options {
        flex-direction: column;
        align-items: center;
    }
    
    .support-option {
        width: 200px;
    }
    
    .budget-options, .urgency-options, .priority-options {
        flex-direction: column;
    }
    
    .budget-option, .urgency-option, .priority-option {
        text-align: center;
    }
}
</style>

<script>
// Comparison Tool Functions
let comparisonList = [];

function addToComparison(contractorId, contractorData) {
    if (comparisonList.length >= 3) {
        showToast('Chỉ có thể so sánh tối đa 3 thợ cùng lúc');
        return;
    }
    
    if (comparisonList.find(c => c.id === contractorId)) {
        showToast('Thợ này đã có trong danh sách so sánh');
        return;
    }
    
    comparisonList.push({
        id: contractorId,
        data: contractorData
    });
    
    updateComparisonButton();
    showToast('Đã thêm vào danh sách so sánh');
}

function removeFromComparison(contractorId) {
    comparisonList = comparisonList.filter(c => c.id !== contractorId);
    updateComparisonButton();
    
    if (comparisonList.length === 0) {
        closeComparison();
    }
}

function updateComparisonButton() {
    const button = document.getElementById('comparisonButton');
    if (button) {
        button.textContent = `So sánh (${comparisonList.length})`;
        button.style.display = comparisonList.length > 0 ? 'block' : 'none';
    }
}

function showComparisonTool() {
    if (comparisonList.length < 2) {
        showToast('Cần chọn ít nhất 2 thợ để so sánh');
        return;
    }
    
    populateComparison();
    document.getElementById('comparisonTool').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeComparison() {
    document.getElementById('comparisonTool').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function populateComparison() {
    // Populate comparison table with contractor data
    comparisonList.forEach((contractor, index) => {
        const headerCell = document.getElementById(`contractor${index + 1}Header`);
        const data = contractor.data;
        
        if (headerCell) {
            headerCell.innerHTML = `
                <div class="contractor-header-compact">
                    <img src="${data.image}" alt="${data.name}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    <div>
                        <strong>${data.name}</strong>
                        <br><small>${data.category}</small>
                    </div>
                </div>
            `;
        }
        
        // Populate other data cells
        document.getElementById(`rating${index + 1}`).innerHTML = generateStars(data.rating) + ` ${data.rating}`;
        document.getElementById(`price${index + 1}`).textContent = data.price_range || 'Liên hệ';
        document.getElementById(`response${index + 1}`).textContent = data.response_time || 'Trong ngày';
        document.getElementById(`experience${index + 1}`).textContent = `${data.years_experience || 'N/A'} năm`;
        document.getElementById(`distance${index + 1}`).textContent = `${data.distance || 'N/A'} km`;
        document.getElementById(`status${index + 1}`).innerHTML = data.is_online ? '🟢 Online' : '⚫ Offline';
    });
}

// AI Recommendation Engine
function getRecommendation() {
    document.getElementById('recommendationEngine').style.display = 'block';
}

function generateRecommendation() {
    const budget = document.querySelector('.budget-option.active')?.dataset.budget;
    const urgency = document.querySelector('.urgency-option.active')?.dataset.urgency;
    const priority = document.querySelector('.priority-option.active')?.dataset.priority;
    
    if (!budget || !urgency || !priority) {
        showToast('Vui lòng trả lời tất cả câu hỏi');
        return;
    }
    
    // Call AI recommendation API
    fetch('/api/contractor-recommendation', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            budget: budget,
            urgency: urgency,
            priority: priority,
            current_search: window.location.search
        })
    })
    .then(response => response.json())
    .then(data => {
        displayRecommendations(data.recommendations);
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra khi tạo gợi ý');
    });
}

function displayRecommendations(recommendations) {
    const container = document.getElementById('recommendedContractors');
    let html = '';
    
    recommendations.forEach((contractor, index) => {
        html += `
            <div class="recommendation-item">
                <div class="rank">#${index + 1}</div>
                <div class="contractor-info">
                    <img src="${contractor.image}" alt="${contractor.name}">
                    <div>
                        <h6>${contractor.name}</h6>
                        <p class="text-muted">${contractor.category}</p>
                        <div class="rating">${generateStars(contractor.rating)} ${contractor.rating}</div>
                    </div>
                </div>
                <div class="match-score">
                    <div class="score">${contractor.match_score}%</div>
                    <small>Phù hợp</small>
                </div>
                <div class="recommendation-reason">
                    <small>${contractor.reason}</small>
                </div>
                <button class="btn btn-sm btn-primary" onclick="quickHire(${contractor.id})">
                    Thuê ngay
                </button>
            </div>
        `;
    });
    
    container.innerHTML = html;
    document.getElementById('recommendationResults').style.display = 'block';
}

// Event Listeners for Recommendation Options
document.addEventListener('DOMContentLoaded', function() {
    // Budget options
    document.querySelectorAll('.budget-option').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.budget-option').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Urgency options
    document.querySelectorAll('.urgency-option').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.urgency-option').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Priority options
    document.querySelectorAll('.priority-option').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.priority-option').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
});

// Expert Consultation
function consultExpert() {
    @auth
        // Open chat or schedule consultation
        window.open('/chat/expert-consultation', '_blank', 'width=400,height=600');
    @else
        window.location.href = '{{ route("user.login") }}';
    @endauth
}

// Export Comparison
function exportComparison() {
    // Generate PDF comparison
    window.print();
}

function proceedWithSelection() {
    // Handle contractor selection from comparison
    showToast('Chức năng đang phát triển');
}
</script> 