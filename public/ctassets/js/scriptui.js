        // Create animated particles
        function createParticles() {
            const container = document.getElementById('particles');
            const particleCount = 40;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Random size
                const size = Math.random() * 8 + 2;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // Random position
                particle.style.left = `${Math.random() * 100}vw`;
                particle.style.top = `${Math.random() * 100}vh`;
                
                // Random animation delay and duration
                particle.style.animationDelay = `${Math.random() * 15}s`;
                particle.style.animationDuration = `${15 + Math.random() * 15}s`;
                
                container.appendChild(particle);
            }
        }

        // Create floating crypto icons
        function createCryptoIcons() {
            const container = document.getElementById('cryptoIcons');
            const iconCount = 15;
            const icons = ['fab fa-bitcoin', 'fab fa-ethereum', 'fab fa-monero', 'fas fa-coins', 'fab fa-bitcoin', 'fas fa-gem', 'fas fa-database', 'fas fa-code'];
            
            for (let i = 0; i < iconCount; i++) {
                const icon = document.createElement('i');
                icon.className = icons[Math.floor(Math.random() * icons.length)] + ' crypto-icon';
                
                // Random size
                const size = Math.random() * 2 + 1.5;
                icon.style.fontSize = `${size}rem`;
                
                // Random position
                icon.style.left = `${Math.random() * 100}vw`;
                
                // Random animation delay and duration
                icon.style.animationDelay = `${Math.random() * 20}s`;
                icon.style.animationDuration = `${20 + Math.random() * 20}s`;
                
                // Random color
                const colors = ['#00d4ff', '#0080ff', '#9d4edd', '#00ff9d', '#ffd700', '#ff00ff'];
                const color = colors[Math.floor(Math.random() * colors.length)];
                icon.style.color = color;
                
                container.appendChild(icon);
            }
        }

        // Initialize Charts
        function initCharts() {
            // Investment Chart
            const investmentCtx = document.getElementById('investmentChart').getContext('2d');
            const investmentChart = new Chart(investmentCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Total Investment',
                        data: [500, 1200, 1800, 2400, 3200, 4100, 5000],
                        borderColor: '#00d4ff',
                        backgroundColor: 'rgba(0, 212, 255, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#00d4ff',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 8
                    }, {
                        label: 'Current Value',
                        data: [550, 1300, 2000, 2600, 3500, 4500, 5500],
                        borderColor: '#9d4edd',
                        backgroundColor: 'rgba(157, 78, 221, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#9d4edd',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#ffffff',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(10, 20, 40, 0.9)',
                            titleColor: '#00d4ff',
                            bodyColor: '#ffffff',
                            borderColor: '#00d4ff',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 212, 255, 0.1)'
                            },
                            ticks: {
                                color: '#a0a0c0',
                                callback: function(value) {
                                    return '$' + value;
                                }
                            },
                            title: {
                                display: true,
                                text: 'Amount ($)',
                                color: '#a0a0c0'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 212, 255, 0.1)'
                            },
                            ticks: {
                                color: '#a0a0c0'
                            },
                            title: {
                                display: true,
                                text: 'Month',
                                color: '#a0a0c0'
                            }
                        }
                    }
                }
            });

            // Performance Chart
            const performanceCtx = document.getElementById('performanceChart').getContext('2d');
            const performanceChart = new Chart(performanceCtx, {
                type: 'bar',
                data: {
                    labels: ['Direct Team', 'Power Leg', 'Weaker Leg', 'Total Network', 'Active Members'],
                    datasets: [{
                        label: 'Current Month',
                        data: [6, 45, 13, 58, 46],
                        backgroundColor: [
                            'rgba(0, 212, 255, 0.7)',
                            'rgba(0, 255, 157, 0.7)',
                            'rgba(255, 77, 125, 0.7)',
                            'rgba(157, 78, 221, 0.7)',
                            'rgba(255, 215, 0, 0.7)'
                        ],
                        borderColor: [
                            '#00d4ff',
                            '#00ff9d',
                            '#ff4d7d',
                            '#9d4edd',
                            '#ffd700'
                        ],
                        borderWidth: 1
                    }, {
                        label: 'Previous Month',
                        data: [4, 32, 8, 40, 32],
                        backgroundColor: 'rgba(255, 255, 255, 0.1)',
                        borderColor: 'rgba(255, 255, 255, 0.3)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#ffffff',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(10, 20, 40, 0.9)',
                            titleColor: '#00d4ff',
                            bodyColor: '#ffffff',
                            borderColor: '#00d4ff',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 212, 255, 0.1)'
                            },
                            ticks: {
                                color: '#a0a0c0'
                            },
                            title: {
                                display: true,
                                text: 'Count',
                                color: '#a0a0c0'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 212, 255, 0.1)'
                            },
                            ticks: {
                                color: '#a0a0c0'
                            },
                            title: {
                                display: true,
                                text: 'Category',
                                color: '#a0a0c0'
                            }
                        }
                    }
                }
            });

            // Chart filter interactions
            document.getElementById('timeFilter').addEventListener('change', function(e) {
                const value = e.target.value;
                const labels = {
                    '7d': ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    '30d': ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    '90d': ['Month 1', 'Month 2', 'Month 3']
                };
                
                const data = {
                    '7d': [[500, 800, 1200, 1800, 2400, 3200, 4100], [550, 850, 1300, 1900, 2500, 3300, 4200]],
                    '30d': [[500, 1200, 2400, 3200], [550, 1300, 2600, 3500]],
                    '90d': [[500, 2400, 4100], [550, 2600, 4500]]
                };
                
                investmentChart.data.labels = labels[value];
                investmentChart.data.datasets[0].data = data[value][0];
                investmentChart.data.datasets[1].data = data[value][1];
                investmentChart.update();
            });

            document.getElementById('performanceFilter').addEventListener('change', function(e) {
                const value = e.target.value;
                
                if (value === 'team') {
                    performanceChart.data.labels = ['Direct Team', 'Power Leg', 'Weaker Leg', 'Total Network', 'Active Members'];
                    performanceChart.data.datasets[0].data = [6, 45, 13, 58, 46];
                    performanceChart.data.datasets[1].data = [4, 32, 8, 40, 32];
                } else if (value === 'personal') {
                    performanceChart.data.labels = ['Direct Income', 'Team Income', 'Bonus', 'Rewards', 'Total'];
                    performanceChart.data.datasets[0].data = [1200, 4500, 800, 500, 7000];
                    performanceChart.data.datasets[1].data = [800, 3200, 600, 300, 4900];
                } else if (value === 'comparison') {
                    performanceChart.data.labels = ['Power Leg', 'Weaker Leg', 'Difference'];
                    performanceChart.data.datasets[0].data = [45, 13, 32];
                    performanceChart.data.datasets[1].data = [32, 8, 24];
                }
                
                performanceChart.update();
            });
        }

        // Toggle sidebar on mobile
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

    const copyBtn = document.getElementById('copyBtn');

if (copyBtn) {
    copyBtn.addEventListener('click', function () {

        const referralInput = document.querySelector('.referral-input');
        if (!referralInput) return;

        referralInput.select();
        referralInput.setSelectionRange(0, 99999);

        navigator.clipboard.writeText(referralInput.value).then(() => {

            const originalText = copyBtn.textContent;
            copyBtn.textContent = '✓ COPIED!';
            copyBtn.style.background = 'linear-gradient(135deg, #00ff9d, #00cc88)';

            setTimeout(() => {
                copyBtn.textContent = originalText;
                copyBtn.style.background = '';
            }, 2000);

        }).catch(() => {
            document.execCommand('copy');
        });
    });
}

        // Add hover effect to cards
        document.querySelectorAll('.dashboard-card, .stat-card, .wallet-card, .action-btn, .leg-stat').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Add active state to nav links
        // Add active state to nav links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.nav-link').forEach(item => {
                    item.classList.remove('active');
                });
                this.classList.add('active');
                
                // Add a ripple effect
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.3);
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    width: ${size}px;
                    height: ${size}px;
                    top: ${y}px;
                    left: ${x}px;
                    pointer-events: none;
                `;
                
                this.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
                
                // Close sidebar on mobile after selection
                if (window.innerWidth <= 992) {
                    document.getElementById('sidebar').classList.remove('active');
                }
            });
        });

        // Dropdown functionality
        document.querySelectorAll('.dropdown-header').forEach(header => {
            header.addEventListener('click', function() {
                // Close other dropdowns
                document.querySelectorAll('.dropdown-content').forEach(content => {
                    if (content !== this.nextElementSibling) {
                        content.classList.remove('open');
                        content.previousElementSibling.classList.remove('active');
                    }
                });
                
                // Toggle current dropdown
                this.classList.toggle('active');
                const content = this.nextElementSibling;
                content.classList.toggle('open');
            });
        });

        // Close dropdown when clicking dropdown links on mobile
        document.querySelectorAll('.dropdown-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 992) {
                    document.getElementById('sidebar').classList.remove('active');
                }
            });
        });

        // Quick actions click
        /*document.querySelectorAll('.action-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const title = this.querySelector('h4').textContent;
                alert(`Action: ${title}. This would perform the action in a real application.`);
            });
        });*/
        // Add ripple animation to CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Initialize particles and animations
        window.addEventListener('DOMContentLoaded', () => {
            createParticles();
            createCryptoIcons();
            initCharts();
            
            // Add subtle animation to cards on load
            document.querySelectorAll('.stat-card, .wallet-card, .action-btn, .leg-stat').forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100 + (index * 100));
            });
            
            // Add random sparkle effects
            setInterval(() => {
                const cards = document.querySelectorAll('.dashboard-card, .investment-card, .chart-container');
                const randomCard = cards[Math.floor(Math.random() * cards.length)];
                
                const sparkle = document.createElement('div');
                sparkle.style.cssText = `
                    position: absolute;
                    width: 10px;
                    height: 10px;
                    background: white;
                    border-radius: 50%;
                    filter: blur(2px);
                    z-index: 1;
                    pointer-events: none;
                    animation: sparkle 1s ease-out forwards;
                `;
                
                sparkle.style.left = `${Math.random() * 90 + 5}%`;
                sparkle.style.top = `${Math.random() * 90 + 5}%`;
                
                randomCard.appendChild(sparkle);
                
                setTimeout(() => sparkle.remove(), 1000);
            }, 3000);
            
            // Add sparkle animation to CSS
            const sparkleStyle = document.createElement('style');
            sparkleStyle.textContent = `
                @keyframes sparkle {
                    0% { transform: scale(0); opacity: 0; }
                    50% { transform: scale(1); opacity: 1; }
                    100% { transform: scale(0); opacity: 0; }
                }
            `;
            document.head.appendChild(sparkleStyle);
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.getElementById('menuToggle');
            
            if (window.innerWidth <= 992 && 
                sidebar.classList.contains('active') && 
                !sidebar.contains(event.target) && 
                !menuToggle.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        });

        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Toggle sidebar with Ctrl+Shift+S
            if (e.ctrlKey && e.shiftKey && e.key === 'S') {
                e.preventDefault();
                document.getElementById('sidebar').classList.toggle('active');
            }
            
            // Copy referral link with Ctrl+Shift+C
            if (e.ctrlKey && e.shiftKey && e.key === 'C') {
                e.preventDefault();
                document.getElementById('copyBtn').click();
            }
        });

    
         
         
         // Function to view transaction details
         function viewTransaction(transactionId) {
            // In a real application, you would open a modal here
            alert(`Viewing transaction: ${transactionId}\n\nThis would open a modal with complete details in a real application.`);
         }
        
         
         // Initialize everything when page loads
         window.addEventListener('DOMContentLoaded', () => {
            if (typeof createParticles === 'function') createParticles();
            if (typeof createCryptoIcons === 'function') createCryptoIcons();
            if (typeof initCharts === 'function') initCharts();
            
            // Show welcome message for table
            setTimeout(() => {
               showMessage('Deposit history loaded successfully', 'info');
            }, 1500);
         });
         
         // Close sidebar when clicking outside on mobile
         document.addEventListener('click', function(event) {
           const sidebar = document.getElementById('sidebar');
           const menuToggle = document.getElementById('menuToggle');
           
           if (window.innerWidth <= 992 && 
               sidebar && sidebar.classList.contains('active') && 
               !sidebar.contains(event.target) && 
               menuToggle && !menuToggle.contains(event.target)) {
               sidebar.classList.remove('active');
           }
         });
   