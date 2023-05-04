import { reactive } from 'vue'
import axios from "axios";
import {usePage} from "@inertiajs/vue3";

export const store = reactive({
    basket: [],
    isLoading: false,

    addProduct(product) {
        const index = this.basket.findIndex(Product => Product.id === product.id);
        if (index >= 0) {
            this.basket[index].count++;
        } else {
            this.basket.push({
                id: product.id,
                name: product.name,
                count: 1,
                price: product.price,
                image: product.image,
            });
        }
        this.saveBasket();
    },
    saveBasket() {
        this.isLoading = true;
        axios.post(route('ajax-basket.add'), {
            user_id: usePage().props.auth.user.id,
            basket: this.basket,
        })
            .catch(error => console.warn(error))
            .finally(() => {
                this.isLoading = false;
            }
        );
    },
    async fetchBasket(customer_id) {
        this.isLoading = true;
        await axios.get(route('ajax-basket.main', {customer_id: customer_id}))
            .then(Response => {
                this.basket = Response.data.length ? JSON.parse(Response.data) : [];
            })
            .finally(() => {
                this.isLoading = false;
            }
        );
    },
    removeFromBasket(product_id) {
        const currentProduct = this.basket.find(product => product.id === product_id);
        if (currentProduct.count === 1) {
            this.remoteFromBasket(product_id);
        } else {
            const index = this.basket.findIndex(product => product.id === product_id);
            this.basket[index].count--;
        }
        this.saveBasket();
    },
    remoteFromBasket(product_id) {
        this.basket = this.basket.filter(product => product.id !== product_id);
        this.saveBasket();
    }
});
