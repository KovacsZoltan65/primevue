export const AuthService = {

    /**
     * Registers a new user by sending a POST request to the registration endpoint.
     *
     * @param {string} name - The name of the user.
     * @param {string} email - The email address of the user.
     * @param {string} password - The password of the user.
     * @param {string} password_confirmation - The confirmation of the user's password.
     * @return {Promise} A promise that resolves with the response from the registration endpoint.
     */
    register(name, email, password, password_confirmation){
        return axios.post('/register', {
            name: name,
            email: email,
            password: password,
            password_confirmation: password_confirmation
        });
    },

    /**
     * Authenticates a user by sending a POST request to the login endpoint.
     *
     * @param {string} email - The user's email address.
     * @param {string} password - The user's password.
     * @return {Promise} A promise that resolves with the server's response.
     */
    login(email, password){
        axios.post('/login', {
            email: email,
            password: password,
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        })
        .then(response => {
            console.log('response', response);
        })
        .catch(error => {
            console.log('error', error);
        });
        //return axios.post('/login', {
        //    email: email,
        //    password: password
        //});
    },

    /**
     * Logs out the current user by sending a POST request to the logout endpoint.
     *
     * @return {Promise} A promise that resolves with the server's response.
     */
    logout(){
        axios.post('/logout', {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        })
            .then(response => {
                console.log('response', response);
                route('login');
            })
            .catch(error => {
                console.log('error', error);
            });
    }

};