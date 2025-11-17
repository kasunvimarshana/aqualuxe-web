import { Card, CardContent } from "@/components/ui/card"
import { Shield, Truck, Heart, Award } from "lucide-react"
import Image from "next/image"

export function About() {
  const features = [
    {
      icon: Shield,
      title: "Health Guarantee",
      description: "99% healthy arrival guarantee with our expert packaging and shipping methods",
    },
    {
      icon: Truck,
      title: "Fast Shipping",
      description: "Express delivery worldwide with temperature-controlled packaging for fish safety",
    },
    {
      icon: Heart,
      title: "Expert Care",
      description: "15+ years of experience in ornamental fish breeding and aquaculture",
    },
    {
      icon: Award,
      title: "Premium Quality",
      description: "Hand-selected fish from certified breeders ensuring the highest quality standards",
    },
  ]

  return (
    <section className="py-16 bg-white">
      <div className="container px-4">
        <div className="grid lg:grid-cols-2 gap-12 items-center">
          <div>
            <h2 className="text-3xl md:text-4xl font-bold mb-6">
              Why Choose{" "}
              <span className="bg-gradient-to-r from-blue-600 to-teal-500 bg-clip-text text-transparent">
                AquaLuxe?
              </span>
            </h2>
            <p className="text-gray-600 mb-8 text-lg">
              With over 15 years of experience in ornamental fish breeding and aquaculture, AquaLuxe has become the
              trusted choice for aquarium enthusiasts worldwide. We specialize in providing premium quality ornamental
              fish with unmatched customer service and expertise.
            </p>

            <div className="grid sm:grid-cols-2 gap-6">
              {features.map((feature) => (
                <Card key={feature.title} className="border-0 shadow-md hover:shadow-lg transition-shadow">
                  <CardContent className="p-6">
                    <feature.icon className="h-8 w-8 text-blue-600 mb-4" />
                    <h3 className="font-semibold mb-2">{feature.title}</h3>
                    <p className="text-sm text-gray-600">{feature.description}</p>
                  </CardContent>
                </Card>
              ))}
            </div>
          </div>

          <div className="relative">
            <div className="absolute inset-0 bg-gradient-to-r from-blue-400/20 to-teal-400/20 rounded-3xl blur-3xl"></div>
            <Image
              src="/placeholder.svg?height=500&width=600"
              alt="AquaLuxe fish farm"
              width={600}
              height={500}
              className="relative rounded-3xl shadow-2xl"
            />
          </div>
        </div>
      </div>
    </section>
  )
}
