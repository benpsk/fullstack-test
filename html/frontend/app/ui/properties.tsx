import Image from "next/image"
import { MapPin } from "lucide-react"
import { Badge } from "@/components/ui/badge"
import { Card, CardContent, CardFooter, CardHeader } from "@/components/ui/card"
import { Property } from "@/types"

export default function Properties({ properties }: { properties: Property[] }) {
    return (
        <div className="container mx-auto py-8 px-4">
            <h2 className="text-2xl font-bold mb-6">Featured Properties</h2>
            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 w-full">
                {properties.map((property) => (
                    <Card key={property.id} className="w-full overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div className="relative h-48 w-full">
                            <Image src={property.photo.full || "/placeholder.svg"} alt={property.title} fill className="object-cover" sizes="md" priority={true} />
                            <Badge className={`absolute top-2 right-2 ${property.for_rent ? "bg-blue-500" : "bg-green-500"}`}>
                                {property.for_rent ? 'RENT' : 'SALE'}
                            </Badge>
                        </div>
                        <CardHeader className="pb-2">
                            <h3 className="font-bold text-lg line-clamp-1">{property.title}</h3>
                        </CardHeader>
                        <CardContent className="space-y-2">
                            <p className="text-sm text-muted-foreground line-clamp-2">{property.description}</p>
                            <div className="flex items-center text-sm text-muted-foreground">
                                <MapPin className="h-4 w-4 mr-1" />
                                <span className="line-clamp-1">{property.location.street + ", " + property.location.province + ", " + property.location.country}</span>
                            </div>
                        </CardContent>
                        <CardFooter className="pt-2 border-t">
                            <p className="font-bold text-lg">{property.currency_symbol  +" "+ property.price}</p>
                        </CardFooter>
                    </Card>
                ))}
            </div>
        </div>

    )
}
