import { Card, CardContent } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import Image from "next/image"
import Link from "next/link"

export function Categories() {
  const categories = [
    {
      name: "Tropical Fish",
      description: "Vibrant and colorful species",
      image: "/placeholder.svg?height=300&width=400",
      href: "/tropical",
      count: "150+ Species",
    },
    {
      name: "Goldfish",
      description: "Classic and elegant varieties",
      image: "/placeholder.svg?height=300&width=400",
      href: "/goldfish",
      count: "50+ Varieties",
    },
    {
      name: "Koi Fish",
      description: "Premium Japanese koi",
      image: "/placeholder.svg?height=300&width=400",
      href: "/koi",
      count: "30+ Premium Koi",
    },
    {
      name: "Marine Fish",
      description: "Exotic saltwater species",
      image: "/placeholder.svg?height=300&width=400",
      href: "/marine",
      count: "80+ Species",
    },
  ]

  return (
    <section className="py-16 bg-white">
      <div className="container px-4">
        <div className="text-center mb-12">
          <h2 className="text-3xl md:text-4xl font-bold mb-4">
            Explore Our{" "}
            <span className="bg-gradient-to-r from-blue-600 to-teal-500 bg-clip-text text-transparent">
              Fish Categories
            </span>
          </h2>
          <p className="text-gray-600 max-w-2xl mx-auto">
            From vibrant tropical fish to majestic koi, discover our carefully curated collection of premium ornamental
            fish
          </p>
        </div>

        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
          {categories.map((category) => (
            <Card key={category.name} className="group hover:shadow-xl transition-all duration-300 border-0 shadow-lg">
              <CardContent className="p-0">
                <div className="relative overflow-hidden rounded-t-lg">
                  <Image
                    src={category.image || "/placeholder.svg"}
                    alt={category.name}
                    width={400}
                    height={300}
                    className="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                  />
                  <div className="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-3 py-1 text-xs font-medium text-blue-600">
                    {category.count}
                  </div>
                </div>
                <div className="p-6">
                  <h3 className="text-xl font-semibold mb-2 group-hover:text-blue-600 transition-colors">
                    {category.name}
                  </h3>
                  <p className="text-gray-600 mb-4">{category.description}</p>
                  <Button
                    asChild
                    variant="outline"
                    className="w-full group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 transition-colors bg-transparent"
                  >
                    <Link href={category.href}>Explore Collection</Link>
                  </Button>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </section>
  )
}
