import { createApp } from 'vue';
import axios from 'axios';  // Assurez-vous d'avoir installé axios avec npm

const LoginApp = {
    data() {
        return {
            email: '',
            password: '',
            errorMessage: ''
        };
    },
    methods: {
        async submitLogin() {
            try {
                const response = await axios.post('/login', {
                    _username: this.email,
                    _password: this.password,
                    _csrf_token: this.csrfToken // Incluez le token CSRF ici si nécessaire
                });
        
                // Gestion de la redirection
                if (response.data.role === 'ROLE_ADMIN') {
                    window.location.href = '/admin/dashboard';
                } else if (response.data.role === 'ROLE_USER') {
                    window.location.href = '/discussions';
                }
        
            } catch (error) {
                if (error.response && error.response.status === 401) {
                    this.errorMessage = "Invalid credentials.";
                } else {
                    this.errorMessage = "An error occurred. Please try again.";
                }
            }
        }
    } 
        
};

createApp(LoginApp).mount('#login-app');
