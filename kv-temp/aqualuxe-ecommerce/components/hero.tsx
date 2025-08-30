import { Button } from "@/components/ui/button"
import { ArrowRight, Star } from "lucide-react"
import Image from "next/image"

export function Hero() {
  return (
    <section className="relative overflow-hidden bg-gradient-to-br from-blue-50 via-teal-50 to-cyan-50">
      <div className="container px-4 py-16 md:py-24">
        <div className="grid lg:grid-cols-2 gap-8 items-center">
          <div className="space-y-6">
            <div className="flex items-center space-x-2">
              <div className="flex">
                {[...Array(5)].map((_, i) => (
                  <Star key={i} className="h-4 w-4 fill-yellow-400 text-yellow-400" />
                ))}
              </div>
              <span className="text-sm text-gray-600">Trusted by 10,000+ customers</span>
            </div>

            <h1 className="text-4xl md:text-6xl font-bold leading-tight">
              Premium{" "}
              <span className="bg-gradient-to-r from-blue-600 to-teal-500 bg-clip-text text-transparent">
                Ornamental Fish
              </span>{" "}
              for Your Aquatic Paradise
            </h1>

            <p className="text-lg text-gray-600 max-w-lg">
              Discover the world's most beautiful and exotic ornamental fish. From vibrant tropical species to elegant
              koi, we bring luxury aquatic life to your home.
            </p>

            <div className="flex flex-col sm:flex-row gap-4">
              <Button
                size="lg"
                className="bg-gradient-to-r from-blue-600 to-teal-500 hover:from-blue-700 hover:to-teal-600"
              >
                Shop Now
                <ArrowRight className="ml-2 h-4 w-4" />
              </Button>
              <Button variant="outline" size="lg">
                View Collection
              </Button>
            </div>

            <div className="flex items-center space-x-8 pt-4">
              <div>
                <div className="text-2xl font-bold text-blue-600">500+</div>
                <div className="text-sm text-gray-600">Fish Species</div>
              </div>
              <div>
                <div className="text-2xl font-bold text-blue-600">15+</div>
                <div className="text-sm text-gray-600">Years Experience</div>
              </div>
              <div>
                <div className="text-2xl font-bold text-blue-600">99%</div>
                <div className="text-sm text-gray-600">Healthy Arrival</div>
              </div>
            </div>
          </div>

          <div className="relative">
            <div className="absolute inset-0 bg-gradient-to-r from-blue-400/20 to-teal-400/20 rounded-3xl blur-3xl"></div>
            <Image
              src="/placeholder.svg?height=600&width=600"
              alt="Beautiful ornamental fish"
              width={600}
              height={600}
              className="relative rounded-3xl shadow-2xl"
            />
          </div>
        </div>
      </div>
    </section>
  )
}
