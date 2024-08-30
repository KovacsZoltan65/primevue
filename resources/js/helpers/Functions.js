export const Functions = {
    createId() {
        let id = '';

        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        for (let i = 0; i < 5; i++) {
            const randomIndex = Math.floor(Math.random() * chars.length);
            id += chars.charAt(randomIndex);
        }

        return id;

        //return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
    }
}