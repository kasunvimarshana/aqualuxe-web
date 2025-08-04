import { Card, CardContent } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { Heart, ShoppingCart, Star } from "lucide-react"
import Image from "next/image"

export function FeaturedProducts() {
  const products = [
    {
      id: 1,
      name: "Rainbow Angelfish",
      price: 89.99,
      originalPrice: 109.99,
      rating: 4.8,
      reviews: 124,
      image: "/placeholder.svg?height=300&width=300",
      badge: "Best Seller",
      inStock: true,
    },
    {
      id: 2,
      name: "Premium Betta Fish",
      price: 45.99,
      rating: 4.9,
      reviews: 89,
      image: "/placeholder.svg?height=300&width=300",
      badge: "New Arrival",
      inStock: true,
    },
    {
      id: 3,
      name: "Neon Tetra School",
      price: 24.99,
      rating: 4.7,
      reviews: 156,
      image: "/placeholder.svg?height=300&width=300",
      inStock: true,
    },
    {
      id: 4,
      name: "Butterfly Koi",
      price: 299.99,
      rating: 5.0,
      reviews: 45,
      image: "/placeholder.svg?height=300&width=300",
      badge: "Premium",
      inStock: false,
    },
    {
      id: 5,
      name: "Discus Fish Pair",
      price: 159.99,
      originalPrice: 189.99,
      rating: 4.6,
      reviews: 78,
      image: "/placeholder.svg?height=300&width=300",
      inStock: true,
    },
    {
      id: 6,
      name: "Clownfish Pair",
      price: 79.99,
      rating: 4.8,
      reviews: 92,
      image: "/placeholder.svg?height=300&width=300",
      badge: "Popular",
      inStock: true,
    },
  ]

  return (
    <section className="py-16 bg-gradient-to-b from-slate-50 to-blue-50">
      <div className="container px-4">
        <div className="text-center mb-12">
          <h2 className="text-3xl md:text-4xl font-bold mb-4">
            Featured{" "}
            <span className="bg-gradient-to-r from-blue-600 to-teal-500 bg-clip-text text-transparent">Products</span>
          </h2>
          <p className="text-gray-600 max-w-2xl mx-auto">
            Handpicked premium ornamental fish that showcase the beauty and elegance of aquatic life
          </p>
        </div>

        <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          {products.map((product) => (
            <Card
              key={product.id}
              className="group hover:shadow-xl transition-all duration-300 border-0 shadow-lg bg-white"
            >
              <CardContent className="p-0">
                <div className="relative overflow-hidden rounded-t-lg">
                  <Image
                    src={product.image || "/placeholder.svg"}
                    alt={product.name}
                    width={300}
                    height={300}
                    className="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300"
                  />
                  {product.badge && (
                    <Badge className="absolute top-4 left-4 bg-blue-600 hover:bg-blue-700">{product.badge}</Badge>
                  )}
                  <div className="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <Button size="icon" variant="secondary" className="rounded-full">
                      <Heart className="h-4 w-4" />
                    </Button>
                  </div>
                  {!product.inStock && (
                    <div className="absolute inset-0 bg-black/50 flex items-center justify-center">
                      <Badge variant="secondary" className="text-white bg-red-600">
                        Out of Stock
                      </Badge>
                    </div>
                  )}
                </div>

                <div className="p-6">
                  <div className="flex items-center mb-2">
                    <div className="flex">
                      {[...Array(5)].map((_, i) => (
                        <Star
                          key={i}
                          className={`h-4 w-4 ${
                            i < Math.floor(product.rating) ? "fill-yellow-400 text-yellow-400" : "text-gray-300"
                          }`}
                        />
                      ))}
                    </div>
                    <span className="text-sm text-gray-600 ml-2">
                      {product.rating} ({product.reviews})
                    </span>
                  </div>

                  <h3 className="text-lg font-semibold mb-2 group-hover:text-blue-600 transition-colors">
                    {product.name}
                  </h3>

                  <div className="flex items-center justify-between mb-4">
                    <div className="flex items-center space-x-2">
                      <span className="text-xl font-bold text-blue-600">${product.price}</span>
                      {product.originalPrice && (
                        <span className="text-sm text-gray-500 line-through">${product.originalPrice}</span>
                      )}
                    </div>
                  </div>

                  <Button
                    className="w-full bg-gradient-to-r from-blue-600 to-teal-500 hover:from-blue-700 hover:to-teal-600"
                    disabled={!product.inStock}
                  >
                    <ShoppingCart className="mr-2 h-4 w-4" />
                    {product.inStock ? "Add to Cart" : "Out of Stock"}
                  </Button>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>

        <div className="text-center mt-12">
          <Button
            variant="outline"
            size="lg"
            className="hover:bg-blue-600 hover:text-white hover:border-blue-600 bg-transparent"
          >
            View All Products
          </Button>
        </div>
      </div>
    </section>
  )
}
