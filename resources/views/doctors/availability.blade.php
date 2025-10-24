<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl leading-tight">
                    <i class="fas fa-user-md mr-2"></i>
                    Doctor Availability Management
                </h2>
                <p class="text-sm opacity-90 mt-1">Manage doctor availability status</p>
            </div>
            <a href="{{ route('dashboard') }}" class="bg-white text-medical-primary px-4 py-2 rounded-lg hover:bg-maroon-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    </x-slot>

    <!-- CSRF Token for AJAX requests -->
    <form id="csrf-form" class="hidden">
        @csrf
    </form>
    
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-toggle-on text-medical-primary mr-2"></i>
                            Doctor Availability Status
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Toggle doctor availability to control their appointment booking status</p>
                    </div>

                    <!-- Status Filter -->
                    <div class="mb-6">
                        <div class="flex items-center space-x-4">
                            <button id="filter-all" class="px-4 py-2 bg-gray-200 rounded-lg text-gray-700 hover:bg-gray-300 transition-colors filter-btn active">
                                All Doctors
                            </button>
                            <button id="filter-available" class="px-4 py-2 bg-gray-200 rounded-lg text-gray-700 hover:bg-gray-300 transition-colors filter-btn">
                                Available
                            </button>
                            <button id="filter-unavailable" class="px-4 py-2 bg-gray-200 rounded-lg text-gray-700 hover:bg-gray-300 transition-colors filter-btn">
                                Unavailable
                            </button>
                        </div>
                    </div>

                    <!-- Doctors Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Doctor
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Department
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Hospital
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($doctors as $doctor)
                                    <tr class="doctor-row {{ $doctor->is_available ? 'available' : 'unavailable' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-medical-light rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user-md text-medical-primary"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        Dr. {{ $doctor->user->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $doctor->specialization }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $doctor->department->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $doctor->department->hospital->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $doctor->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $doctor->is_available ? 'Available' : 'Unavailable' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <!-- JavaScript toggle button -->
                                            <button 
                                                class="toggle-availability px-4 py-2 rounded-md {{ $doctor->is_available ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} transition-colors"
                                                data-doctor-id="{{ $doctor->id }}"
                                                data-status="{{ $doctor->is_available ? 'available' : 'unavailable' }}"
                                            >
                                                <i class="fas {{ $doctor->is_available ? 'fa-toggle-off' : 'fa-toggle-on' }} mr-1"></i>
                                                {{ $doctor->is_available ? 'Set Unavailable' : 'Set Available' }}
                                            </button>
                                            
                                            <!-- Fallback form for non-JS browsers -->
                                            <noscript>
                                                <form method="POST" action="{{ route('doctors.toggle-availability', $doctor->id) }}" class="mt-2">
                                                    @csrf
                                                    <button type="submit" class="px-4 py-2 rounded-md {{ $doctor->is_available ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} transition-colors">
                                                        <i class="fas {{ $doctor->is_available ? 'fa-toggle-off' : 'fa-toggle-on' }} mr-1"></i>
                                                        {{ $doctor->is_available ? 'Set Unavailable' : 'Set Available' }}
                                                    </button>
                                                </form>
                                            </noscript>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            No doctors found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter buttons
            const filterButtons = document.querySelectorAll('.filter-btn');
            const doctorRows = document.querySelectorAll('.doctor-row');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    const filterId = this.id;
                    
                    // Show/hide rows based on filter
                    doctorRows.forEach(row => {
                        if (filterId === 'filter-all') {
                            row.style.display = '';
                        } else if (filterId === 'filter-available' && row.classList.contains('available')) {
                            row.style.display = '';
                        } else if (filterId === 'filter-unavailable' && row.classList.contains('unavailable')) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            });
            
            // Toggle availability buttons
            const toggleButtons = document.querySelectorAll('.toggle-availability');
            
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const doctorId = this.getAttribute('data-doctor-id');
                    const currentStatus = this.getAttribute('data-status');
                    
                    // Get CSRF token from the form
                    const csrfToken = document.querySelector('#csrf-form input[name="_token"]').value;
                    const formData = new FormData();
                    formData.append('_token', csrfToken);
                    
                    // Send AJAX request to toggle availability
                    fetch(`/doctors/${doctorId}/toggle-availability`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Update UI
                            const row = this.closest('.doctor-row');
                            const statusCell = row.querySelector('td:nth-child(4) span');
                            
                            if (data.is_available) {
                                // Doctor is now available
                                statusCell.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
                                statusCell.textContent = 'Available';
                                this.className = 'toggle-availability px-4 py-2 rounded-md bg-red-100 text-red-700 hover:bg-red-200 transition-colors';
                                this.innerHTML = '<i class="fas fa-toggle-off mr-1"></i> Set Unavailable';
                                this.setAttribute('data-status', 'available');
                                row.classList.remove('unavailable');
                                row.classList.add('available');
                            } else {
                                // Doctor is now unavailable
                                statusCell.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800';
                                statusCell.textContent = 'Unavailable';
                                this.className = 'toggle-availability px-4 py-2 rounded-md bg-green-100 text-green-700 hover:bg-green-200 transition-colors';
                                this.innerHTML = '<i class="fas fa-toggle-on mr-1"></i> Set Available';
                                this.setAttribute('data-status', 'unavailable');
                                row.classList.remove('available');
                                row.classList.add('unavailable');
                            }
                            
                            // Show success message
                            showMessage(data.message, 'success');
                        } else {
                            // Show error message
                            showMessage(data.message || 'An error occurred while updating doctor availability', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showMessage('An error occurred while updating doctor availability: ' + error.message, 'error');
                    });
                    
                    // Helper function to show messages
                    function showMessage(message, type) {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = `fixed top-4 right-4 px-4 py-3 rounded z-50 ${type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'}`;
                        messageDiv.innerHTML = `
                            <span class="block sm:inline">${message}</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <i class="fas fa-times cursor-pointer"></i>
                            </span>
                        `;
                        document.body.appendChild(messageDiv);
                        
                        // Add click event to close button
                        const closeButton = messageDiv.querySelector('.fa-times');
                        if (closeButton) {
                            closeButton.addEventListener('click', () => messageDiv.remove());
                        }
                        
                        // Remove message after 5 seconds
                        setTimeout(() => {
                            if (messageDiv.parentNode) {
                                messageDiv.remove();
                            }
                        }, 5000);
                    }
                });
            });
        });
    </script>

    <style>
        .filter-btn.active {
            background-color: #722c2c;
            color: white;
        }
    </style>
</x-app-layout>
