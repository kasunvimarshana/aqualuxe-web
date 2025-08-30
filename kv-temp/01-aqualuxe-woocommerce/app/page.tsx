import Image from "next/image"
import { Button } from "@/components/ui/button"
import { Card, CardContent } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { Star, Fish, Waves, Shield, Truck, Award } from "lucide-react"

export default function HomePage() {
  const featuredProducts = [
    {
      id: 1,
      name: "Premium Koi Collection",
      price: "$299.99",
      originalPrice: "$399.99",
      image: "/placeholder.svg?height=300&width=300",
      rating: 5,
      badge: "Best Seller",
    },
    {
      id: 2,
      name: "Exotic Betta Varieties",
      price: "$89.99",
      originalPrice: "$119.99",
      image: "/placeholder.svg?height=300&width=300",
      rating: 5,
      badge: "New Arrival",
    },
    {
      id: 3,
      name: "Tropical Angelfish Pairs",
      price: "$149.99",
      originalPrice: "$199.99",
      image: "/placeholder.svg?height=300&width=300",
      rating: 4,
      badge: "Limited",
    },
    {
      id: 4,
      name: "Goldfish Premium Mix",
      price: "$59.99",
      originalPrice: "$79.99",
      image: "/placeholder.svg?height=300&width=300",
      rating: 5,
      badge: "Popular",
    },
  ]

  const categories = [
    {
      name: "Koi Fish",
      image: "/placeholder.svg?height=200&width=300",
      count: "25+ varieties",
    },
    {
      name: "Tropical Fish",
      image: "/placeholder.svg?height=200&width=300",
      count: "50+ species",
    },
    {
      name: "Goldfish",
      image: "/placeholder.svg?height=200&width=300",
      count: "15+ types",
    },
    {
      name: "Aquarium Supplies",
      image: "/placeholder.svg?height=200&width=300",
      count: "100+ products",
    },
  ]

  return (
    <div className="min-h-screen bg-gradient-to-b from-blue-50 to-white">
      {/* Hero Section */}
      <section className="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-teal-600 text-white">
        <div className="absolute inset-0 bg-black/20"></div>
        <div className="relative container mx-auto px-4 py-20 lg:py-32">
          <div className="grid lg:grid-cols-2 gap-12 items-center">
            <div className="space-y-8">
              <div className="space-y-4">
                <Badge className="bg-white/20 text-white border-white/30 hover:bg-white/30">
                  Premium Ornamental Fish
                </Badge>
                <h1 className="text-4xl lg:text-6xl font-bold leading-tight">
                  Discover the
                  <span className="block text-yellow-300">Luxury of AquaLuxe</span>
                </h1>
                <p className="text-xl text-blue-100 max-w-lg">
                  Premium ornamental fish and aquarium supplies for enthusiasts worldwide. Transform your space with
                  living art.
                </p>
              </div>
              <div className="flex flex-col sm:flex-row gap-4">
                <Button size="lg" className="bg-yellow-500 hover:bg-yellow-600 text-black font-semibold">
                  Shop Collection
                </Button>
                <Button
                  size="lg"
                  variant="outline"
                  className="border-white text-white hover:bg-white hover:text-blue-700 bg-transparent"
                >
                  View Catalog
                </Button>
              </div>
              <div className="flex items-center gap-8 text-sm">
                <div className="flex items-center gap-2">
                  <Shield className="h-5 w-5" />
                  <span>Live Arrival Guarantee</span>
                </div>
                <div className="flex items-center gap-2">
                  <Truck className="h-5 w-5" />
                  <span>Worldwide Shipping</span>
                </div>
              </div>
            </div>
            <div className="relative">
              <Image
                src="/placeholder.svg?height=500&width=600"
                alt="Premium Ornamental Fish"
                width={600}
                height={500}
                className="rounded-2xl shadow-2xl"
              />
              <div className="absolute -bottom-6 -left-6 bg-white text-black p-4 rounded-xl shadow-lg">
                <div className="flex items-center gap-2">
                  <Award className="h-6 w-6 text-yellow-500" />
                  <div>
                    <div className="font-semibold">Award Winning</div>
                    <div className="text-sm text-gray-600">Quality Guarantee</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div className="absolute bottom-0 left-0 right-0">
          <Waves className="w-full h-16 text-white" />
        </div>
      </section>

      {/* Categories Section */}
      <section className="py-16 bg-white">
        <div className="container mx-auto px-4">
          <div className="text-center mb-12">
            <h2 className="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Shop by Category</h2>
            <p className="text-gray-600 max-w-2xl mx-auto">
              Explore our carefully curated collection of premium ornamental fish and aquarium essentials
            </p>
          </div>
          <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            {categories.map((category, index) => (
              <Card
                key={index}
                className="group cursor-pointer hover:shadow-xl transition-all duration-300 border-0 shadow-lg"
              >
                <CardContent className="p-0">
                  <div className="relative overflow-hidden rounded-t-lg">
                    <Image
                      src={category.image || "/placeholder.svg"}
                      alt={category.name}
                      width={300}
                      height={200}
                      className="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <div className="absolute bottom-4 left-4 text-white">
                      <h3 className="font-semibold text-lg">{category.name}</h3>
                      <p className="text-sm opacity-90">{category.count}</p>
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Featured Products */}
      <section className="py-16 bg-gray-50">
        <div className="container mx-auto px-4">
          <div className="text-center mb-12">
            <h2 className="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Featured Collection</h2>
            <p className="text-gray-600 max-w-2xl mx-auto">
              Handpicked premium ornamental fish from our exclusive collection
            </p>
          </div>
          <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            {featuredProducts.map((product) => (
              <Card
                key={product.id}
                className="group cursor-pointer hover:shadow-xl transition-all duration-300 border-0 shadow-lg"
              >
                <CardContent className="p-0">
                  <div className="relative overflow-hidden rounded-t-lg">
                    <Image
                      src={product.image || "/placeholder.svg"}
                      alt={product.name}
                      width={300}
                      height={300}
                      className="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300"
                    />
                    <Badge className="absolute top-3 left-3 bg-blue-600 hover:bg-blue-700">{product.badge}</Badge>
                  </div>
                  <div className="p-4 space-y-3">
                    <h3 className="font-semibold text-lg text-gray-900 group-hover:text-blue-600 transition-colors">
                      {product.name}
                    </h3>
                    <div className="flex items-center gap-1">
                      {[...Array(5)].map((_, i) => (
                        <Star
                          key={i}
                          className={`h-4 w-4 ${i < product.rating ? "text-yellow-400 fill-current" : "text-gray-300"}`}
                        />
                      ))}
                      <span className="text-sm text-gray-600 ml-2">({product.rating}.0)</span>
                    </div>
                    <div className="flex items-center gap-2">
                      <span className="text-xl font-bold text-blue-600">{product.price}</span>
                      <span className="text-sm text-gray-500 line-through">{product.originalPrice}</span>
                    </div>
                    <Button className="w-full bg-blue-600 hover:bg-blue-700">Add to Cart</Button>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Features Section */}
      <section className="py-16 bg-white">
        <div className="container mx-auto px-4">
          <div className="grid md:grid-cols-3 gap-8">
            <div className="text-center space-y-4">
              <div className="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto">
                <Shield className="h-8 w-8 text-blue-600" />
              </div>
              <h3 className="text-xl font-semibold">Live Arrival Guarantee</h3>
              <p className="text-gray-600">
                100% guarantee that your fish arrive healthy and vibrant, or we'll replace them free.
              </p>
            </div>
            <div className="text-center space-y-4">
              <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                <Truck className="h-8 w-8 text-green-600" />
              </div>
              <h3 className="text-xl font-semibold">Worldwide Shipping</h3>
              <p className="text-gray-600">
                Expert packaging and fast, secure delivery to fish enthusiasts around the globe.
              </p>
            </div>
            <div className="text-center space-y-4">
              <div className="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto">
                <Award className="h-8 w-8 text-yellow-600" />
              </div>
              <h3 className="text-xl font-semibold">Premium Quality</h3>
              <p className="text-gray-600">
                Award-winning breeding programs ensuring the highest quality ornamental fish.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Newsletter Section */}
      <section className="py-16 bg-gradient-to-r from-blue-600 to-teal-600 text-white">
        <div className="container mx-auto px-4 text-center">
          <div className="max-w-2xl mx-auto space-y-6">
            <Fish className="h-12 w-12 mx-auto text-yellow-300" />
            <h2 className="text-3xl font-bold">Stay Updated with AquaLuxe</h2>
            <p className="text-blue-100">
              Get exclusive access to new arrivals, care tips, and special offers for fish enthusiasts.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
              <input type="email" placeholder="Enter your email" className="flex-1 px-4 py-3 rounded-lg text-black" />
              <Button className="bg-yellow-500 hover:bg-yellow-600 text-black font-semibold px-8">Subscribe</Button>
            </div>
          </div>
        </div>
      </section>
    </div>
  )
}
